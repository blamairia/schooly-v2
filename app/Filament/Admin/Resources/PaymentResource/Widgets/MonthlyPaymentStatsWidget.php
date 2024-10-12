<?php
namespace App\Filament\Widgets;

use App\Models\Payment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class MonthlyPaymentStatsWidget extends BaseWidget
{
    protected function getCards(): array
    {
        $totalRevenue = Payment::whereBetween('created_at', [now()->startOfMonth(), now()])->sum('amount_paid');
        $totalDue = Payment::whereBetween('created_at', [now()->startOfMonth(), now()])->sum('amount_due');
        $totalMadePayments = Payment::whereBetween('created_at', [now()->startOfMonth(), now()])->where('amount_paid', '>', 0)->count();

        $totalCashPaid = Payment::where('payment_method_id', 1)->whereBetween('created_at', [now()->startOfMonth(), now()])->sum('amount_paid');
        $totalCardPaid = Payment::where('payment_method_id', 2)->whereBetween('created_at', [now()->startOfMonth(), now()])->sum('amount_paid');
        $totalCheckPaid = Payment::where('payment_method_id', 3)->whereBetween('created_at', [now()->startOfMonth(), now()])->sum('amount_paid');

        return [
            Card::make('Total Revenue', number_format($totalRevenue, 2))->description('Total Paid Amount This Month')->color('success'),
            Card::make('Total Due', number_format($totalDue, 2))->description('Total Due This Month')->color('danger'),
            Card::make('Total Payments Made', $totalMadePayments)->description('Payments This Month')->color('primary'),
            Card::make('Cash Paid', number_format($totalCashPaid, 2))->description('Cash Payments This Month')->color('primary'),
            Card::make('Card Paid', number_format($totalCardPaid, 2))->description('Card Payments This Month')->color('primary'),
            Card::make('Check Paid', number_format($totalCheckPaid, 2))->description('Check Payments This Month')->color('primary'),
        ];
    }
}
