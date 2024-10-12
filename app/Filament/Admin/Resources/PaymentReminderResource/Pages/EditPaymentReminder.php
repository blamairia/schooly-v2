<?php

namespace App\Filament\Admin\Resources\PaymentReminderResource\Pages;

use App\Filament\Admin\Resources\PaymentReminderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPaymentReminder extends EditRecord
{
    protected static string $resource = PaymentReminderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
