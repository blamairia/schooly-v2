<?php

namespace App\Filament\Admin\Resources\ParentsResource\Widgets;

use App\Models\Parents;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ParentStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Parents', Parents::count())
                ->description('Registered guardians')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),
            
            Stat::make('New This Month', Parents::whereMonth('created_at', now()->month)->count())
                ->description('New registrations')
                ->color('success'),
        ];
    }
}
