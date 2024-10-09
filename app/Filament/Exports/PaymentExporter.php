<?php

namespace App\Filament\Exports;

use App\Models\Payment;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class PaymentExporter extends Exporter
{
    protected static ?string $model = Payment::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('student_full_name')
            ->label('Full Name')
            ->state(function (Payment $record) {
                // Check if student relationship exists and concatenate first and last names
                return $record->student
                    ? "{$record->student->first_name} {$record->student->last_name}"
                    : 'N/A';
            }),
            ExportColumn::make('payment_type_name')
            ->label('Payment Type')
            ->state(function (Payment $record) {
                // Ensure the payment type relationship exists
                return $record->paymentType->name ?? 'N/A';
            }),
            ExportColumn::make('payment_method')
                ->label('Payment Method')
                ->state(function ($record) {
                    // Map payment_method_id to readable names
                    $paymentMethods = [
                        1 => 'Cash',
                        2 => 'Card',
                        3 => 'Check',
                    ];

                    // Return the readable payment method name based on the payment_method_id
                    return $paymentMethods[$record->payment_method_id] ?? 'Unknown';
                }),


            ExportColumn::make('total_amount')->label('Total Amount'),
            ExportColumn::make('amount_paid')->label('Amount Paid'),
            ExportColumn::make('amount_due')->label('Amount Due'),
            ExportColumn::make('status')->label('Status'),
            ExportColumn::make('due_date')->label('Due Date'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your payment export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
