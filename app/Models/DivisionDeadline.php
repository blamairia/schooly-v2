<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DivisionDeadline extends Model
{
    protected $table = 'division_deadlines'; // Explicitly set the table name

    protected $fillable = [
        'division_plan_id',
        'part_number',
        'due_date',
    ];

    // Define the inverse relationship with DivisionPlan
    public function divisionPlan()
    {
        return $this->belongsTo(DivisionPlan::class, 'division_plan_id');
    }
}
