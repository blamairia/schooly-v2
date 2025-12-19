<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Payment;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PaymentStatsWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';

    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        // Revenue This Month
        $revenueThisMonth = Payment::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount_paid');
        
        $revenueLastMonth = Payment::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('amount_paid');
            
        $revenueChange = $revenueLastMonth > 0 
            ? (($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100 
            : 0;

        // Total Students
        $totalStudents = \App\Models\Student::count();
        $newStudentsThisMonth = \App\Models\Student::whereMonth('created_at', now()->month)->count();

        // Outstanding Debt
        $totalDebt = Payment::sum('amount_due');

        // Total Revenue All Time
        $totalRevenueAllTime = Payment::sum('amount_paid');

        return [
            Stat::make('Monthly Revenue', number_format($revenueThisMonth, 2) . ' DZD')
                ->description($revenueChange >= 0 ? '+' . number_format($revenueChange, 1) . '% increase' : number_format($revenueChange, 1) . '% decrease')
                ->descriptionIcon($revenueChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->chart([$revenueLastMonth, $revenueThisMonth])
                ->color($revenueChange >= 0 ? 'success' : 'danger'),

            Stat::make('Total Students', $totalStudents)
                ->description('+' . $newStudentsThisMonth . ' new this month')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),

            Stat::make('Outstanding Debt', number_format($totalDebt, 2) . ' DZD')
                ->description('Total Unpaid Amounts')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('danger'),
                
            Stat::make('Total Revenue (All Time)', number_format($totalRevenueAllTime, 2) . ' DZD')
                ->description('Lifetime Earnings')
                ->color('success'),
        ];
    }
}
