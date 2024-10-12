<?php

namespace App\Filament\Admin\Resources\PaymentReminderResource\Pages;

use App\Filament\Admin\Resources\PaymentReminderResource;
use App\Filament\Admin\Resources\PaymentResource;
use Filament\Resources\Pages\EditRecord;

class EditPaymentReminder extends EditRecord
{
    protected static string $resource = PaymentReminderResource::class;

    public function mount($record): void
    {
        parent::mount($record);

        // Perform the auto-redirect to the payment edit page
        $paymentId = $this->record->id;

        // Redirect to the payments edit page using PaymentResource::getUrl
        redirect()->to(PaymentResource::getUrl('edit', ['record' => $paymentId]));
    }
}

