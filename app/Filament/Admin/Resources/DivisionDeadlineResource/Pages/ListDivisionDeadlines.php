<?php

namespace App\Filament\Admin\Resources\DivisionDeadlineResource\Pages;

use App\Filament\Admin\Resources\DivisionDeadlineResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDivisionDeadlines extends ListRecords
{
    protected static string $resource = DivisionDeadlineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
