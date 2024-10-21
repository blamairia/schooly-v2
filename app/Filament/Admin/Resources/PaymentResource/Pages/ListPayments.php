<?php

namespace App\Filament\Admin\Resources\PaymentResource\Pages;

use App\Filament\Admin\Resources\PaymentResource;
use App\Filament\Widgets\AllTimePaymentStatsWidget;
use App\Filament\Widgets\PaymentStatss;
use App\Filament\Widgets\TodaysPaymentStatsWidget;
use App\Filament\Widgets\WeeklyPaymentStatsWidget;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListPayments extends ListRecords
{
    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }





    // Function to dynamically return the correct widget based on the active tab


    // Method to get the active tab
    protected function getActiveTab(): string {
        return request()->query('activeTab', 'All'); // Fetch the active tab from URL query parameter, default to 'All'
    }

    // Define tabs and their corresponding queries
    public function getTabs(): array {
        return [
            'All' => Tab::make(),
            'Today' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->whereDate('created_at', now())),
            'This Week' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->whereBetween('created_at', [now()->startOfWeek(), now()])),
            'This Month' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->whereBetween('created_at', [now()->startOfMonth(), now()])),
            'Paid' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('amount_due', 0)),
        ];
    }


}
