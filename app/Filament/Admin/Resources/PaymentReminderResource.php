<?php

namespace App\Filament\Admin\Resources;

use App\Models\Payment;
use Filament\Resources\Resource;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Admin\Resources\PaymentReminderResource\Pages;


class PaymentReminderResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationLabel = 'Payment Reminders';

    protected static ?string $navigationIcon = 'heroicon-o-bell';

    protected static ?string $navigationGroup = 'Payments';

    public static function getEloquentQuery(): Builder
{
    return parent::getEloquentQuery()
        ->whereIn('status', ['unpaid', 'partial'])
        ->where(function ($query) {
            $query->where('due_date', '<', now()) // Overdue payments
                ->orWhereBetween('due_date', [now(), now()->addDays(7)]) // Due within 7 days
                ->orWhereBetween('due_date', [now()->addDays(8), now()->addDays(15)]); // Due within 8 to 15 days
        });
}


public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('student.first_name')->label('First Name'),
            TextColumn::make('student.last_name')->label('Last Name'),
            TextColumn::make('paymentType.name')->label('Payment Type'),
            TextColumn::make('total_amount')->label('Total Amount'),
            TextColumn::make('amount_due')->label('Amount Due'),

                BadgeColumn::make('due_date')
                ->label('Due Status')
                ->colors([
                    'danger' => fn ($record) => $record->due_date->lessThan(now()), // Overdue
                    'warning' => fn ($record) => $record->due_date->between(now(), now()->addDays(7)), // Due in 7 days
                    'info' => fn ($record) => $record->due_date->between(now()->addDays(8), now()->addDays(15)), // Due in 8-15 days
                ])
                ->formatStateUsing(function ($record) {
                    $formattedDate = $record->due_date->format('Y-m-d');
                    if ($record->due_date->lessThan(now())) {
                        return 'Overdue (Due: ' . $formattedDate . ')';
                    } elseif ($record->due_date->between(now(), now()->addDays(7))) {
                        return 'Due in 7 Days (Due: ' . $formattedDate . ')';
                    } elseif ($record->due_date->between(now()->addDays(8), now()->addDays(15))) {
                        return 'Due in 8-15 Days (Due: ' . $formattedDate . ')';
                    }
                    return 'No Due Status (Due: ' . $formattedDate . ')';
                }),

            TextColumn::make('status')->label('Status'),
        ])->filters([
            Tables\Filters\Filter::make('overdue')
            ->label('Overdue')
            ->query(fn (Builder $query) => $query->where('due_date', '<', now())),
        Tables\Filters\Filter::make('due_within_7_days')
            ->label('Due in 7 Days')
            ->query(fn (Builder $query) => $query->whereBetween('due_date', [now(), now()->addDays(7)])),
        Tables\Filters\Filter::make('due_within_15_days')
            ->label('Due in 15 Days')
            ->query(fn (Builder $query) => $query->whereBetween('due_date', [now()->addDays(8), now()->addDays(15)])),
        ],layout: FiltersLayout::AboveContent)->defaultSort('due_date', 'asc');
}


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentReminders::route('/'),
            'create' => Pages\CreatePaymentReminder::route('/create'),
            'edit' => Pages\EditPaymentReminder::route('/{record}/edit'),
        ];
    }
}
