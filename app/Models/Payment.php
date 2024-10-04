<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'student_id',
        'payment_type',
        'division_plan_id',
        'part_number',
        'total_amount',
        'amount_due',
        'amount_paid',
        'status',
        'due_date',
        'payment_method',
    ];
    public function divisionPlan()
{
    return $this->belongsTo(DivisionPlan::class);
}

public function paymentType()
{
    return $this->belongsTo(PaymentType::class);
}

public function student()
{
    return $this->belongsTo(Student::class);
}

}
