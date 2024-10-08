<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'birth_date',
        'birth_place',
        'address',
        'parent_id',
        'study_year_id',
        'class_assigned_id',
        'phone_number',
        'cassier_id',
        'cassier_expiration',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Parents::class);
    }

    public function classAssigned(): BelongsTo
        {
            return $this->belongsTo(StudyClass::class, 'class_assigned_id');
        }


    public function studyYear(): BelongsTo
    {
        return $this->belongsTo(StudyYear::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
    public function paymentTotals(): HasMany
    {
        return $this->hasMany(PaymentTotal::class);
    }

    public function getPaymentTotalForType($paymentTypeId)
{
    // Find the payment totals for this student and the given payment type
    $paymentTotal = $this->payments()
        ->where('payment_type_id', $paymentTypeId)
        ->sum('amount_paid'); // Assuming you want to sum the paid amount

    return $paymentTotal;
}

    public function cassier(): BelongsTo
    {
        return $this->belongsTo(Cassier::class);
    }

    public function getFullName(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
