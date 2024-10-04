<?php

namespace App\Filament\Admin\Resources\DivisionPlanResource\Pages;

use App\Filament\Admin\Resources\DivisionPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDivisionPlan extends EditRecord
{
    protected static string $resource = DivisionPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
