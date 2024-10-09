<?php

namespace App\Filament\Exports;

use App\Models\PaymentTotal;
use App\Models\PaymentType;
use App\Models\Student;
use Carbon\Carbon;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;

class StudentExporter extends Exporter
{
    protected static ?string $model = Student::class;

    /**
     * Define the columns for export.
     *
     * @return array
     */
    public static function getColumns(): array
    {
        // Fetch available payment types
        $paymentTypes = PaymentType::all();

        // Static columns for student details
        $columns = [
            ExportColumn::make('first_name')->label('First Name'),
            ExportColumn::make('last_name')->label('Last Name'),
            ExportColumn::make('full_name')
                ->label('Full Name')
                ->state(fn (Student $record) => "{$record->first_name} {$record->last_name}"),
            ExportColumn::make('classAssigned.name')->label('Class Assigned'),
            ExportColumn::make('cassier.number')->label('Cassier Number'),
        ];

        // Add dynamic columns for each payment type
        foreach ($paymentTypes as $paymentType) {
            $columns[] = ExportColumn::make("payment_type_{$paymentType->id}_total")
                ->label($paymentType->name)
                ->state(function (Student $record) use ($paymentType) {
                    // Fetch pre-calculated totals from payment_totals table
                    $paymentTotal = PaymentTotal::where('student_id', $record->id)
                        ->where('payment_type_id', $paymentType->id)
                        ->value('total_amount');

                    return $paymentTotal ? number_format($paymentTotal, 2) : '0.00';
                });
        }

        return $columns;
    }

    /**
     * Customize the export query.
     *
     * @param Builder $query
     * @return Builder
     */
    public static function modifyQuery(Builder $query): Builder
    {
        // Eager load related data for performance
        return $query->with(['classAssigned', 'cassier']);
    }

    /**
     * Customize the export filename.
     *
     * @return string
     */
    public  function getFileNames(): string
    {
        return 'students_export_' . Carbon::now()->format('Y_m_d_His') . '.xlsx';
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your student export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
