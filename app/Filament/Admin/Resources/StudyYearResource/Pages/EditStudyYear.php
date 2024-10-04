<?php

namespace App\Filament\Admin\Resources\StudyYearResource\Pages;

use App\Filament\Admin\Resources\StudyYearResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStudyYear extends EditRecord
{
    protected static string $resource = StudyYearResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
