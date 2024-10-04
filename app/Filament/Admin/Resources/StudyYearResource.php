<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\StudyYearResource\Pages;
use App\Filament\Admin\Resources\StudyYearResource\RelationManagers;
use App\Models\StudyYear;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudyYearResource extends Resource
{
    protected static ?string $model = StudyYear::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('year')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudyYears::route('/'),
            'create' => Pages\CreateStudyYear::route('/create'),
            'edit' => Pages\EditStudyYear::route('/{record}/edit'),
        ];
    }
}
