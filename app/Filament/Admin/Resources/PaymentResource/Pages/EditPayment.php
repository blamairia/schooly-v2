<?php

namespace App\Filament\Admin\Resources\PaymentResource\Pages;

use App\Filament\Admin\Resources\PaymentResource;
use App\Models\Payment;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditPayment extends EditRecord
{
    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Action::make('print')
                    ->icon('heroicon-o-printer')
                    ->label('Print Receipt')
                    ->url(fn (Payment $record) => route('payments.print', ['payment' => $record->id]))
                    ->openUrlInNewTab(),
        ];
    }
}
