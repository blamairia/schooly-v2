<?php
namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CassierResource\Pages;
use App\Models\Cassier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Validation\Rule;

class CassierResource extends Resource
{
    protected static ?string $model = Cassier::class;

    protected static ?string $navigationIcon = 'heroicon-o-lock-closed';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Add validation rule for uniqueness
                Forms\Components\TextInput::make('number')
                    ->required()
                    ->label('Cassier Number')
                    ->unique(
                        table: Cassier::class,
                        column: 'number',
                        ignoreRecord: true // Ensure the current record being edited is ignored
                    )
                    ->numeric() // Assuming cassier numbers are numeric, you can remove this if not needed
                    ->rules(['max:255']), // Add other rules as needed

                Forms\Components\Select::make('student_id')
                    ->relationship('student', 'first_name')
                    ->nullable()
                    ->reactive(), // React to form changes

                Forms\Components\DatePicker::make('rented_from')
                    ->nullable()
                    ->reactive(), // React to form changes

                Forms\Components\DatePicker::make('rented_until')
                    ->nullable()
                    ->reactive(), // React to form changes

                // Use a hidden input to handle is_rented
                Forms\Components\Hidden::make('is_rented')
                    ->default(false)
                    ->afterStateHydrated(function ($set, $get) {
                        // Set is_rented to true only if student_id and both dates are filled
                        if ($get('student_id') && $get('rented_from') && $get('rented_until')) {
                            $set('is_rented', true);
                        } else {
                            $set('is_rented', false);
                        }
                    })
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number'),
                Tables\Columns\TextColumn::make('student.first_name')->label('Rented By'),
                Tables\Columns\TextColumn::make('rented_from'),
                Tables\Columns\TextColumn::make('rented_until'),
                Tables\Columns\BooleanColumn::make('is_rented')->label('Is Rented'),
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
            'index' => Pages\ListCassiers::route('/'),
            'create' => Pages\CreateCassier::route('/create'),
            'edit' => Pages\EditCassier::route('/{record}/edit'),
        ];
    }
}
