<?php

namespace App\Filament\Widgets;

use App\Models\PaymentTotal;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PaymentStats extends BaseWidget
{
    protected function getStats(): array
    {
        // Calculate total revenue and total payments
        $totalRevenue = PaymentTotal::sum('total_amount');
        $totalDue = PaymentTotal::sum('total_due');
        $totalPartsPaid = PaymentTotal::sum('parts_paid');

        return [
            Stat::make('Total Revenue', number_format($totalRevenue, 2))
                ->description('Total revenue collected')
                ->descriptionIcon('heroicon-s-currency-dollar')
                ->color('success'),

            Stat::make('Total Due', number_format($totalDue, 2))
                ->description('Total due amount')
                ->descriptionIcon('heroicon-s-exclamation-triangle')
                ->color('warning'),

            Stat::make('Total Parts Paid', number_format($totalPartsPaid))
                ->description('Number of payment parts made')
                ->color('info'),
        ];
    }
}
