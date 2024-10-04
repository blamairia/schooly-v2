<?php

namespace App\Filament\Admin\Resources\PaymentTypeResource\Pages;

use App\Filament\Admin\Resources\PaymentTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPaymentTypes extends ListRecords
{
    protected static string $resource = PaymentTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
