<?php

namespace App\Filament\Admin\Resources\ParentsResource\Pages;

use App\Filament\Admin\Resources\ParentsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListParents extends ListRecords
{
    protected static string $resource = ParentsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ParentsResource\Widgets\ParentStatsOverview::class,
        ];
    }
}
