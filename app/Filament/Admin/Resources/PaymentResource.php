<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PaymentResource\Pages;
use App\Filament\Admin\Resources\PaymentResource\RelationManagers;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('student_id')
                ->relationship('student', 'full_name')
                ->getOptionLabelFromRecordUsing(fn($record) => "{$record->first_name} {$record->last_name}")
                ->required(),

                Forms\Components\Select::make('payment_type_id')
                    ->relationship('paymentType', 'name')
                    ->required(),
                Forms\Components\Select::make('division_plan_id')
                    ->relationship('divisionPlan', 'name')
                    ->required(),
                Forms\Components\TextInput::make('part_number')->required(),
                Forms\Components\TextInput::make('total_amount')->required()->numeric(),
                Forms\Components\TextInput::make('amount_due')->numeric(),
                Forms\Components\TextInput::make('amount_paid')->numeric(),
                Forms\Components\DatePicker::make('due_date')->required(),
                Forms\Components\TextInput::make('status')->required(),
                Forms\Components\Select::make('payment_method')->options([
                    'cash' => 'Cash',
                    'card' => 'Card',
                    'check' => 'Check',
                ])->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.first_name'),
                Tables\Columns\TextColumn::make('paymentType.name'),
                Tables\Columns\TextColumn::make('total_amount'),
                Tables\Columns\TextColumn::make('amount_due'),
                Tables\Columns\TextColumn::make('amount_paid'),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('due_date'),
                Tables\Columns\TextColumn::make('payment_method'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
