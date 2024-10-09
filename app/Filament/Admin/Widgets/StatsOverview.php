<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget

{
    use InteractsWithPageFilters;


    protected function getStats(): array
    {
        return [
            Stat::make('Total Revenue', '$1,200.00')
                ->description('From Jan 1, 2021 to Jan 31, 2021')
                ->descriptionIcon('heroicon-s-currency-dollar')
                ->color('success'),
        ];
    }
}
