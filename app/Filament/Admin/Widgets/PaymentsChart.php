<?php
namespace App\Filament\Admin\Widgets;

use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use App\Models\Payment;
use Illuminate\Support\Facades\Cache;

class PaymentsChart extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static string $chartId = 'paymentsChart';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Payments for the Last 3 Months';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        // Cache key with a 1-hour expiry (you can adjust this)
        return Cache::remember('payments_chart_data', 60 * 60, function () {
            $startOfThreeMonthsAgo = now()->subMonths(3)->startOfMonth();
            $endOfCurrentMonth = now()->endOfMonth();

            // Query payments for the last 3 months
            $payments = Payment::whereBetween('created_at', [$startOfThreeMonthsAgo, $endOfCurrentMonth])
                ->selectRaw('MONTH(created_at) as month, SUM(amount_paid) as total_paid')
                ->groupByRaw('MONTH(created_at)')
                ->orderByRaw('MONTH(created_at)')
                ->pluck('total_paid', 'month')
                ->toArray();

            // Create an array for the last 3 months
            $months = [
                now()->subMonths(2)->format('M'),
                now()->subMonths(1)->format('M'),
                now()->format('M'),
            ];

            // Ensure all months are filled (even if no payments)
            $data = [];
            foreach ([now()->subMonths(2), now()->subMonths(1), now()] as $month) {
                $data[] = $payments[$month->month] ?? 0;
            }

            return [
                'chart' => [
                    'type' => 'bar',
                    'height' => 300,
                ],
                'series' => [
                    [
                        'name' => 'Total Payments',
                        'data' => $data, // Data for the last 3 months
                    ],
                ],
                'xaxis' => [
                    'categories' => $months, // Last 3 months names
                    'labels' => [
                        'style' => [
                            'fontFamily' => 'inherit',
                        ],
                    ],
                ],
                'yaxis' => [
                    'labels' => [
                        'style' => [
                            'fontFamily' => 'inherit',
                        ],
                    ],
                ],
                'colors' => ['#f59e0b'],
                'plotOptions' => [
                    'bar' => [
                        'borderRadius' => 3,
                        'horizontal' => true,
                    ],
                ],
            ];
        });
    }
}
