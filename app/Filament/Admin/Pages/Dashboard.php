<?php
namespace App\Filament\Admin\Pages;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

class Dashboard extends \Filament\Pages\Dashboard
{
    use HasFiltersForm;

    public function filtersForm(Form $form): Form
    {
        return $form->schema([
            Section::make()
                ->schema([
                    DatePicker::make('startDate')
                        ->label('Date From')
                        ->default(now()->startOfMonth()),
                    DatePicker::make('endDate')
                        ->label('Date To')
                        ->default(now()->endOfMonth()),
                    Toggle::make('today')
                        ->label('Today')
                        ->reactive()
                        ->afterStateUpdated(fn ($state, callable $set) => $state ? $set('from_date', today()) && $set('to_date', today()) : null),
                ])
                ->columns(2),
        ]);
    }

    public function updatedFilters(array $filters): void
    {
        // Trigger widget update when filters change
        $this->emit('refreshPaidPaymentChart', $filters);
    }
}
