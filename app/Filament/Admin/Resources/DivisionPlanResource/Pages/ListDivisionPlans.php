<?php

namespace App\Filament\Admin\Resources\DivisionPlanResource\Pages;

use App\Filament\Admin\Resources\DivisionPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDivisionPlans extends ListRecords
{
    protected static string $resource = DivisionPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
