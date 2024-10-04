<?php

namespace App\Filament\Admin\Resources\StudentResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms;
use Filament\Forms\Form;
use App\Filament\Admin\Resources\PaymentResource;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('payment_type.name')->label('Payment Type')->sortable(),
                TextColumn::make('total_amount')->label('Total Amount')->sortable(),
                TextColumn::make('amount_paid')->label('Amount Paid')->sortable(),
                TextColumn::make('status')->label('Payment Status')->sortable(),
                TextColumn::make('due_date')->label('Due Date')->dateTime(),
                TextColumn::make('created_at')->label('Payment Date')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getEagerRelations(): array
    {
        return ['student'];  // Eager load student relationship
    }
}
