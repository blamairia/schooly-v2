<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\DivisionDeadlineResource\Pages;
use App\Models\DivisionDeadline;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DivisionDeadlineResource extends Resource
{
    protected static ?string $model = DivisionDeadline::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('division_plan_id')
                    ->relationship('divisionPlan', 'name')
                    ->required(),
                Forms\Components\TextInput::make('part_number')->required()->numeric(),
                Forms\Components\DatePicker::make('due_date')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('divisionPlan.name')->label('Division Plan'),
                Tables\Columns\TextColumn::make('part_number'),
                Tables\Columns\TextColumn::make('due_date')->date(),
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
            'index' => Pages\ListDivisionDeadlines::route('/'),
            'create' => Pages\CreateDivisionDeadline::route('/create'),
            'edit' => Pages\EditDivisionDeadline::route('/{record}/edit'),
        ];
    }
}
