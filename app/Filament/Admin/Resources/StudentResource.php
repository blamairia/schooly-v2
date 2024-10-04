<?php


namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\StudentResource\Pages;
use App\Filament\Admin\Resources\StudentResource\RelationManagers;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('first_name')->required(),
                Forms\Components\TextInput::make('last_name')->required(),
                Forms\Components\DatePicker::make('birth_date')->required(),
                Forms\Components\TextInput::make('birth_place')->required(),
                Forms\Components\TextInput::make('phone_number')->required(),
                Forms\Components\Select::make('parent_id')
                    ->relationship('parent', 'first_name') // This line initializes the relationship
                    ->getOptionLabelFromRecordUsing(fn($record) => "{$record->first_name} {$record->last_name}") // Concatenate first and last names
                    ->required(),





                Forms\Components\Select::make('class_assigned_id')
                    ->relationship('classAssigned', 'name')
                    ->required(),
                Forms\Components\Select::make('study_year_id')
                    ->relationship('studyYear', 'year')
                    ->required(),
                Forms\Components\Select::make('cassier_id')
                    ->relationship('cassier', 'number')
                    ->nullable(),
                Forms\Components\DatePicker::make('cassier_expiration')->nullable(),
                Forms\Components\TextInput::make('address')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name'),
                Tables\Columns\TextColumn::make('last_name'),
                Tables\Columns\TextColumn::make('classAssigned.name'),
                Tables\Columns\TextColumn::make('studyYear.year'),
                Tables\Columns\TextColumn::make('cassier.number')->label('Cassier'),
                Tables\Columns\TextColumn::make('cassier_expiration')->label('Cassier Expiration'),
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
        return [
            RelationManagers\PaymentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}
