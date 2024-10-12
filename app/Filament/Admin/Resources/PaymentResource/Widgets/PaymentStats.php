<?php
namespace App\Filament\Widgets;

use App\Models\Payment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class PaymentStats extends BaseWidget

{

    // Method to create the stats overview cards for all-time stats
    protected function getCards(): array
    {
        // Get the total stats for all payments
        $totalRevenue = Payment::sum('amount_paid');
        $totalDue = Payment::sum('amount_due');
        $totalMadePayments = Payment::where('amount_paid', '>', 0)->count();


        // Get payment method breakdown (Assuming PaymentMethod IDs: 1 => Cash, 2 => Card, 3 => Check)
        $totalCashPaid = Payment::where('payment_method_id', 1)->sum('amount_paid');
        $totalCardPaid = Payment::where('payment_method_id', 2)->sum('amount_paid');
        $totalCheckPaid = Payment::where('payment_method_id', 3)->sum('amount_paid');

        return [
            // Total Revenue and Due Stats
            Card::make('Total Revenue', number_format($totalRevenue, 2))
                ->description('Total Paid Amount')
                ->color('success'),

            Card::make('Total Due', number_format($totalDue, 2))
                ->description('Total Due Amount')
                ->color('danger'),

            Card::make('Total Payments Made', $totalMadePayments)->description('Total Payments')->color('primary'),


            // Cash Payment Stats
            Card::make('Cash Paid', number_format($totalCashPaid, 2))
                ->description('Total Paid via Cash')
                ->color('primary'),

            // Card Payment Stats
            Card::make('Card Paid', number_format($totalCardPaid, 2))
                ->description('Total Paid via Card')
                ->color('primary'),

            // Check Payment Stats
            Card::make('Check Paid', number_format($totalCheckPaid, 2))
                ->description('Total Paid via Check')
                ->color('primary'),
        ];
    }
}
