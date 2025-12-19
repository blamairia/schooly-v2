<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ReminderResource\Pages;
use App\Models\Reminder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ReminderResource extends Resource
{
    protected static ?string $model = Reminder::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell-alert';

    protected static ?string $navigationGroup = 'Payments';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('division_deadline_id')
                    ->relationship('divisionDeadline', 'due_date')
                    ->label('Division Deadline')
                    ->required(),
                Forms\Components\DatePicker::make('reminder_date')->required(),
                Forms\Components\Toggle::make('sent')->label('Sent'),
                Forms\Components\Textarea::make('reason')->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('divisionDeadline.due_date')->label('Due Date'),
                Tables\Columns\TextColumn::make('reminder_date'),
                Tables\Columns\BooleanColumn::make('sent'),
                Tables\Columns\TextColumn::make('reason'),
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
            'index' => Pages\ListReminders::route('/'),
            'create' => Pages\CreateReminder::route('/create'),
            'edit' => Pages\EditReminder::route('/{record}/edit'),
        ];
    }
}
