<?php
// app/Observers/DivisionDeadlineObserver.php

namespace App\Observers;

use App\Models\DivisionDeadline;
use App\Models\Payment;

class DivisionDeadlineObserver
{
    /**
     * Handle the updated event for the DivisionDeadline model.
     *
     * @param  \App\Models\DivisionDeadline  $divisionDeadline
     * @return void
     */
    public function updated(DivisionDeadline $divisionDeadline)
    {
        // Find all payments related to this division deadline
        $payments = Payment::where('division_plan_id', $divisionDeadline->division_plan_id)
            ->where('part_number', $divisionDeadline->part_number)
            ->get();

        // Update the due date for all related payments
        foreach ($payments as $payment) {
            $payment->due_date = $divisionDeadline->due_date;
            $payment->save();
        }
    }
}
