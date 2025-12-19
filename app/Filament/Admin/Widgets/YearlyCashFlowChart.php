<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Payment;
use Carbon\Carbon;

class YearlyCashFlowChart extends ChartWidget
{
    protected static ?string $heading = 'Yearly Cash Flow (2025)';

    protected static ?string $maxHeight = '500px';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'md' => 2,
    ];

    protected function getData(): array
    {
        $year = now()->year;
        $startOfYear = Carbon::create($year, 1, 1)->startOfDay();
        $endOfYear = Carbon::create($year, 12, 31)->endOfDay();

        // Query payments for the current year
        $payments = Payment::whereBetween('created_at', [$startOfYear, $endOfYear])
            ->selectRaw('MONTH(created_at) as month, SUM(amount_paid) as total_paid')
            ->groupByRaw('MONTH(created_at)')
            ->orderByRaw('MONTH(created_at)')
            ->pluck('total_paid', 'month')
            ->toArray();

        // Create an array for Jan-Dec
        $months = [];
        $data = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $date = Carbon::create($year, $i, 1);
            $months[] = $date->format('M');
            $data[] = (float) ($payments[$i] ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Cash Flow',
                    'data' => $data,
                    'fill' => 'start',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)', // Blue-500 with opacity
                    'borderColor' => '#3b82f6', // Blue-500
                    'tension' => 0.4,
                ],
            ],
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'maintainAspectRatio' => true,
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
        ];
    }
}
