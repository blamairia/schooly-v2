<?php

namespace App\Filament\Admin\Resources\CassierResource\Pages;

use App\Filament\Admin\Resources\CassierResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCassier extends EditRecord
{
    protected static string $resource = CassierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
