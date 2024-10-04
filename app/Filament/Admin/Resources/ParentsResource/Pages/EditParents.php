<?php

namespace App\Filament\Admin\Resources\ParentsResource\Pages;

use App\Filament\Admin\Resources\ParentsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditParents extends EditRecord
{
    protected static string $resource = ParentsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
