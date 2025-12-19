<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\StudyClass;

class StudentsPerClassChart extends ChartWidget
{
    protected static ?string $heading = 'Students per Class';

    protected static ?string $maxHeight = '500px';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'md' => 2,
    ];

    protected function getData(): array
    {
        $data = StudyClass::withCount('students')->get();
        
        $labels = $data->pluck('name')->toArray();
        $counts = $data->pluck('students_count')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Students',
                    'data' => $counts,
                    'backgroundColor' => [
                        '#3b82f6', '#10b981', '#f59e0b', '#ef4444', 
                        '#8b5cf6', '#ec4899', '#6366f1', '#14b8a6', 
                        '#f97316', '#84cc16', '#06b6d4', '#a855f7',
                        '#f43f5e', '#22c55e', '#0ea5e9', '#d946ef', 
                        '#eab308', '#64748b', '#fb7185'
                    ],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'maintainAspectRatio' => true,
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
        ];
    }
}
