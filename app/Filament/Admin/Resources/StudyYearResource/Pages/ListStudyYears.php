<?php

namespace App\Filament\Admin\Resources\StudyYearResource\Pages;

use App\Filament\Admin\Resources\StudyYearResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStudyYears extends ListRecords
{
    protected static string $resource = StudyYearResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
