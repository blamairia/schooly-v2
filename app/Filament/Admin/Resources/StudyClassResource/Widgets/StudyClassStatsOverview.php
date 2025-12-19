<?php

namespace App\Filament\Admin\Resources\StudyClassResource\Widgets;

use App\Models\StudyClass;
use App\Models\Student;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StudyClassStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalClasses = StudyClass::count();
        $totalStudents = Student::count();
        $avgStudents = $totalClasses > 0 ? round($totalStudents / $totalClasses) : 0;

        return [
            Stat::make('Total Classes', $totalClasses)
                ->description('Active rooms')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('primary'),
            
            Stat::make('Avg Students / Class', $avgStudents)
                ->description('Class density')
                ->color($avgStudents > 30 ? 'warning' : 'success'),
        ];
    }
}
