<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Payment extends Model
{
    use LogsActivity;
    protected $fillable = [
        'student_id',
        'payment_type_id',
        'division_plan_id',
        'part_number',
        'total_amount',
        'amount_due',
        'amount_paid',
        'year',
        'status',
        'study_year_id',
        'due_date',
        'payment_method_id', // Use payment_method_id for the relationship with PaymentMethod
    ];
    protected $casts = [
        'due_date' => 'datetime', // Cast due_date as a Carbon instance
    ];

    // Define relationships
    public function divisionPlan(): BelongsTo
    {
        return $this->belongsTo(DivisionPlan::class);
    }

    public function paymentType(): BelongsTo
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function studyYear(): BelongsTo
    {
        return $this->belongsTo(StudyYear::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }
    public function getActivitylogOptions(): LogOptions
{
    return LogOptions::defaults()
        ->logFillable();
}

    // Automatically boot the model to handle created, updated, and deleted events
    public static function boot()
    {
        parent::boot();
        static::saving(function ($payment) {
            if ($payment->total_amount < $payment->amount_paid) {
                throw ValidationException::withMessages([
                    'total_amount' => 'Total amount cannot be less than the amount already paid.',
                ]);
            }

            // Recalculate amount_due and update status
            $payment->amount_due = max($payment->total_amount - $payment->amount_paid, 0);
            $payment->status = $payment->amount_paid >= $payment->total_amount
                ? 'paid'
                : ($payment->amount_paid > 0 ? 'partial' : 'unpaid');
        });


        static::created(function ($payment) {
            $payment->adjustPaymentTotals('add', $payment->getAttributes());
            $payment->updateMethodSpecificTotals(
                $payment->paymentMethod->method_name,
                $payment->amount_paid,
                $payment->amount_due,
                $payment->part_number,
                'add'
            );
        });

        static::updated(function ($payment) {
            $payment->handlePaymentUpdate();
        });

        static::deleted(function ($payment) {
            $payment->adjustPaymentTotals('subtract', $payment->getAttributes());
            $payment->updateMethodSpecificTotals(
                $payment->paymentMethod->method_name,
                $payment->amount_paid,
                $payment->amount_due,
                $payment->part_number,
                'subtract'
            );
        });
    }

    // Handle payment update, considering old and new values
    public function handlePaymentUpdate()
    {
        $original = $this->getOriginal();

        // Subtract the old values from the totals
        $this->adjustPaymentTotals('subtract', $original);

        // Handle payment method change
        if ($this->isDirty('payment_method_id')) {
            $oldPaymentMethod = PaymentMethod::find($original['payment_method_id']);
            $newPaymentMethod = $this->paymentMethod;

            // Subtract old values from the old payment method totals
            if ($oldPaymentMethod) {
                $this->updateMethodSpecificTotals(
                    $oldPaymentMethod->method_name,
                    $original['amount_paid'],
                    $original['amount_due'],
                    $original['part_number'],
                    'subtract'
                );
            }

            // Add new values to the new payment method totals
            if ($newPaymentMethod) {
                $this->updateMethodSpecificTotals(
                    $newPaymentMethod->method_name,
                    $this->amount_paid,
                    $this->amount_due,
                    $this->part_number,
                    'add'
                );
            }
        } else {
            // If payment method has not changed, adjust method-specific totals with old and new values
            // Subtract old values
            $this->updateMethodSpecificTotals(
                $this->paymentMethod->method_name,
                $original['amount_paid'],
                $original['amount_due'],
                $original['part_number'],
                'subtract'
            );
            // Add new values
            $this->updateMethodSpecificTotals(
                $this->paymentMethod->method_name,
                $this->amount_paid,
                $this->amount_due,
                $this->part_number,
                'add'
            );
        }

        // Add the new values to the totals
        $this->adjustPaymentTotals('add', $this->getAttributes());
    }

    // Adjust totals in the common payment_totals table
    public function adjustPaymentTotals($operation, $values)
    {
        $amountPaid = $values['amount_paid'] ?? 0;
        $amountDue = $values['amount_due'] ?? 0;
        $partNumber = $values['part_number'] ?? 0;
        $totalPartsExpression = $operation === 'add' ? 'total_parts + 1' : 'total_parts - 1';

        DB::table('payment_totals')->updateOrInsert(
            [
                'student_id' => $values['student_id'] ?? $this->student_id,
                'payment_type_id' => $values['payment_type_id'] ?? $this->payment_type_id,
                'study_year_id' => $values['study_year_id'] ?? $this->study_year_id,
            ],
            [
                'total_amount' => DB::raw('total_amount ' . ($operation === 'add' ? '+' : '-') . ' ' . $amountPaid),
                'total_due' => DB::raw('total_due ' . ($operation === 'add' ? '+' : '-') . ' ' . $amountDue),
                'parts_paid' => DB::raw('parts_paid ' . ($operation === 'add' ? '+' : '-') . ' ' . $partNumber),
                'total_parts' => DB::raw($totalPartsExpression),
                'updated_at' => now(),
            ]
        );
    }

    // Helper function to update the payment totals based on method
    private function updateMethodSpecificTotals($method, $amountPaid, $amountDue, $partNumber, $operation = 'add')
    {
        $table = 'payment_totals_' . strtolower($method);
        $amountPaidExpression = $operation === 'add' ? 'total_amount + ' . $amountPaid : 'total_amount - ' . $amountPaid;
        $amountDueExpression = $operation === 'add' ? 'total_due + ' . $amountDue : 'total_due - ' . $amountDue;
        $partNumberExpression = $operation === 'add' ? 'parts_paid + ' . $partNumber : 'parts_paid - ' . $partNumber;
        $totalPartsExpression = $operation === 'add' ? 'total_parts + 1' : 'total_parts - 1';

        DB::table($table)->updateOrInsert(
            [
                'student_id' => $this->student_id,
                'payment_type_id' => $this->payment_type_id,
                'study_year_id' => $this->study_year_id,
            ],
            [
                'total_amount' => DB::raw($amountPaidExpression),
                'total_due' => DB::raw($amountDueExpression),
                'parts_paid' => DB::raw($partNumberExpression),
                'total_parts' => DB::raw($totalPartsExpression),
                'updated_at' => now(),
            ]
        );
    }
}
