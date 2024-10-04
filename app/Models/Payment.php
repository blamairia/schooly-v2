<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'student_id',
        'payment_type_id',
        'division_plan_id',
        'part_number',
        'total_amount',
        'amount_due',
        'amount_paid',
        'status',
        'due_date',
        'payment_method',
    ];

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

    // Add this relationship for fetching division deadlines
    public function divisionDeadline()
{
    return $this->hasOne(DivisionDeadline::class, 'division_plan_id', 'division_plan_id')
                ->where('part_number', $this->part_number);
}

}
