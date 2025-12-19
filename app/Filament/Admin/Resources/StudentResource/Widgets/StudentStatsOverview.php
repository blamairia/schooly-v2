<?php

namespace App\Filament\Admin\Resources\StudentResource\Widgets;

use App\Models\Student;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StudentStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Students', Student::count())
                ->description('Active enrollments')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('primary'),
            
            Stat::make('New This Month', Student::whereMonth('created_at', now()->month)->count())
                ->description('Growth rate')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('External Students', Student::where('external', true)->count())
                ->description('From other schools')
                ->color('warning'),
        ];
    }
}
