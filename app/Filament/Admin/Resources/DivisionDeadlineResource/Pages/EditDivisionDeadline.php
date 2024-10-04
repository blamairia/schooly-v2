<?php

namespace App\Filament\Admin\Resources\DivisionDeadlineResource\Pages;

use App\Filament\Admin\Resources\DivisionDeadlineResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDivisionDeadline extends EditRecord
{
    protected static string $resource = DivisionDeadlineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
