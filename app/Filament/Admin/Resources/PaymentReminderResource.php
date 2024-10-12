<?php

namespace App\Filament\Admin\Resources;

use App\Models\Payment;
use Filament\Resources\Resource;
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
            ->whereBetween('due_date', [now(), now()->addDays(15)]);
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
                TextColumn::make('due_date')->label('Due Date')->date(),
                TextColumn::make('status')->label('Status'),
            ]);
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
