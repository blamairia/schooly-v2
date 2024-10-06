<?php

namespace App\Filament\Admin\Resources\StudentResource\Pages;

use App\Filament\Admin\Resources\StudentResource;
use App\Models\Payment;
use App\Models\Student;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;

    protected function handleRecordCreation(array $data): Student
    {
        // Start a transaction
        return DB::transaction(function () use ($data) {
            // Create the student
            $student = Student::create($data);

            // Handle payments (if there are any in the request)
            if (isset($data['payments'])) {
                foreach ($data['payments'] as $paymentData) {
                    // Assign student ID and create payment
                    $paymentData['student_id'] = $student->id;
                    Payment::create($paymentData);
                }
            }

            return $student;
        });
    }

    protected function getFormData(array $data): array
    {
        // Dehydrate form fields and include hidden fields like amount_due
        return parent::getFormData($data);
    }

    protected function afterCreate(): void
    {
        Notification::make()
            ->title('Student and payments created successfully!')
            ->success()
            ->send();
    }
}
