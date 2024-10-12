<?php

namespace App\Filament\Admin\Resources\PaymentReminderResource\Pages;

use App\Filament\Admin\Resources\PaymentReminderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPaymentReminders extends ListRecords
{
    protected static string $resource = PaymentReminderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
