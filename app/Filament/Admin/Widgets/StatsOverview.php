<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Payment;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget

{
    protected static ?string $pollingInterval = '3s'; // Refresh every 3 seconds

    protected function getCards(): array
    {
        $totalRevenue = Payment::whereDate('created_at', today())->sum('amount_paid');
        $totalDue = Payment::whereDate('created_at', today())->sum('amount_due');
        $totalMadePayments = Payment::whereDate('created_at', today())->where('amount_paid', '>', 0)->count();

        $totalCashPaid = Payment::where('payment_method_id', 1)->whereDate('created_at', today())->sum('amount_paid');
        $totalCardPaid = Payment::where('payment_method_id', 2)->whereDate('created_at', today())->sum('amount_paid');
        $totalCheckPaid = Payment::where('payment_method_id', 3)->whereDate('created_at', today())->sum('amount_paid');

        return [
            Card::make('Total Payments Made', $totalMadePayments)->description('Payments Today')->color('primary'),
            Card::make('Cash Paid', number_format($totalCashPaid, 2))->description('Cash Payments Today')->color('primary'),
            Card::make('Card Paid', number_format($totalCardPaid, 2))->description('Card Payments Today')->color('primary'),
            Card::make('Check Paid', number_format($totalCheckPaid, 2))->description('Check Payments Today')->color('primary'),
        ];
    }
}
