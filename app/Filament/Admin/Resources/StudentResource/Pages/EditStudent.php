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
        ];
    }
}

