<?php

namespace App\Filament\Admin\Resources\StudentResource\Pages;

use App\Filament\Admin\Resources\StudentResource;
use App\Models\Student;
use App\Models\Payment;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;
use Filament\Actions;

class EditStudent extends EditRecord
{
    protected static string $resource = StudentResource::class;

    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        // Use DB transaction to ensure atomicity
        return DB::transaction(function () use ($record, $data) {
            // Update student
            $record->update($data);

            // Handle payments
            if (isset($data['payments'])) {
                foreach ($data['payments'] as $paymentData) {
                    $payment = Payment::find($paymentData['id'] ?? null);

                    if ($payment) {
                        // Update existing payment
                        $payment->update($paymentData);
                    } else {
                        // Create new payment if it doesn't exist
                        $paymentData['student_id'] = $record->id;
                        Payment::create($paymentData);
                    }
                }
            }

            return $record;
        });
    }

    protected function afterSave(): void
    {
        Notification::make()
            ->title('Student and payments updated successfully!')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),

            // Add Print Receipts Action
             // Add Print Receipts Action
             Actions\Action::make('printReceipts')
                ->label('Print Receipts')
                ->icon('heroicon-o-printer')
                ->url(function () {
                    // Gather payment IDs for the current student
                    $paymentIds = Payment::where('student_id', $this->record->id)->pluck('id')->toArray();

                    // If there are no payments, show a notification
                    if (empty($paymentIds)) {
                        Notification::make()
                            ->title('No payments available for this student.')
                            ->warning()
                            ->send();
                        return null;
                    }

                    // Generate the URL for bulk print
                    return route('print.bulk.receipts', ['paymentIds' => $paymentIds]);
                })
                ->openUrlInNewTab(),


            ];
    }
}
