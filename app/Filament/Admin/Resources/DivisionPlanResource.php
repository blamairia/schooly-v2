<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\DivisionPlanResource\Pages;
use App\Models\DivisionPlan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DivisionPlanResource extends Resource
{
    protected static ?string $model = DivisionPlan::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('total_parts')->required()->numeric(),
                Forms\Components\HasManyRepeater::make('deadlines')
                ->relationship('deadlines') // This should match the method name in the model
                ->schema([
                    Forms\Components\TextInput::make('part_number')
                        ->required(),
                    Forms\Components\DatePicker::make('due_date')
                        ->required(),
                ])
                ->minItems(1)
                ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('total_parts'),
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
            'index' => Pages\ListDivisionPlans::route('/'),
            'create' => Pages\CreateDivisionPlan::route('/create'),
            'edit' => Pages\EditDivisionPlan::route('/{record}/edit'),
        ];
    }
}
