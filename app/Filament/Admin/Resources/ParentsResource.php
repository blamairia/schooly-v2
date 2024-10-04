<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ParentsResource\Pages;
use App\Models\Parents;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ParentsResource extends Resource
{
    protected static ?string $model = Parents::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('first_name')->required(),
                Forms\Components\TextInput::make('last_name')->required(),
                Forms\Components\DatePicker::make('birth_date')->required(),
                Forms\Components\TextInput::make('birth_place')->required(),
                Forms\Components\TextInput::make('phone_number')->required(),
                Forms\Components\TextInput::make('address')->required(),
                Forms\Components\TextInput::make('email')->email()->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name'),
                Tables\Columns\TextColumn::make('last_name'),
                Tables\Columns\TextColumn::make('email'),
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
            'index' => Pages\ListParents::route('/'),
            'create' => Pages\CreateParents::route('/create'),
            'edit' => Pages\EditParents::route('/{record}/edit'),
        ];
    }
}
