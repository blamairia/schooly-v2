<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\StudyClassResource\Pages;
use App\Models\StudyClass;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StudyClassResource extends Resource
{
    protected static ?string $model = StudyClass::class;
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationGroup = 'Students';

    protected static ?string $navigationLabel = 'Classes';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudyClasses::route('/'),
            'create' => Pages\CreateStudyClass::route('/create'),
            'edit' => Pages\EditStudyClass::route('/{record}/edit'),
        ];
    }
}
