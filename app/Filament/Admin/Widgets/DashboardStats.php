<?php

namespace App\Filament\Widgets;

use App\Models\PaymentTotal;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class DashboardStats extends BaseWidget
{
    public ?string $startDate = null;
    public ?string $endDate = null;

    protected function getStats(): array
    {
        // Parse the start and end date from the filter or use defaults
        $startDate = Carbon::parse($this->startDate ?: now()->startOfMonth());
        $endDate = Carbon::parse($this->endDate ?: now());

        // Calculate stats based on the date range
        $totalRevenue = PaymentTotal::whereBetween('created_at', [$startDate, $endDate])->sum('total_amount');
        $totalDue = PaymentTotal::whereBetween('created_at', [$startDate, $endDate])->sum('total_due');
        $totalPartsPaid = PaymentTotal::whereBetween('created_at', [$startDate, $endDate])->sum('parts_paid');

        return [
            Stat::make('Total Revenue', number_format($totalRevenue, 2))
                ->description("From {$startDate->format('M d, Y')} to {$endDate->format('M d, Y')}")
                ->descriptionIcon('heroicon-s-currency-dollar')
                ->color('success'),

            Stat::make('Total Due', number_format($totalDue, 2))
                ->description('Outstanding amount')
                ->descriptionIcon('heroicon-s-exclamation-triangle')
                ->color('warning'),

            Stat::make('Total Parts Paid', $totalPartsPaid)
                ->description('Number of payment parts made')
                ->descriptionIcon('heroicon-s-clipboard-check')
                ->color('info'),
        ];
    }

    // Optional: If you want live updates
    protected static ?string $pollingInterval = null;  // Set this to '10s' for live polling
}
