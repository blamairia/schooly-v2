<?php

namespace App\Filament\Admin\Resources\StudentResource\Pages;

use App\Filament\Admin\Resources\StudentResource;
use Filament\Resources\Pages\Page;
use pxlrbt\FilamentActivityLog\Pages\ListActivities;

class StudentLogPage extends ListActivities
{
    protected static string $resource = StudentResource::class;

}
