<?php

namespace App\Filament\Admin\Resources\PaymentResource\Pages;

use App\Filament\Admin\Resources\PaymentResource;
use App\Filament\Widgets\AllTimePaymentStatsWidget;
use App\Filament\Widgets\PaymentRemindersWidget;
use App\Filament\Widgets\TodaysPaymentStatsWidget;
use App\Filament\Widgets\WeeklyPaymentStatsWidget;
use App\Filament\Widgets\MonthlyPaymentStatsWidget;
use App\Filament\Widgets\PaymentStats;
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

    public static function getWidgets(): array {
        return [
            PaymentStats::class,
            TodaysPaymentStatsWidget::class,
            WeeklyPaymentStatsWidget::class,
            MonthlyPaymentStatsWidget::class,
            PaymentRemindersWidget::class,
        ];
    }



    // Function to dynamically return the correct widget based on the active tab
    protected function getHeaderWidgets(): array {

        $activeTab = $this->getActiveTab();

        switch ($activeTab) {
            case 'Today':
                return [TodaysPaymentStatsWidget::class];
            case 'This Week':
                return [WeeklyPaymentStatsWidget::class];
            case 'This Month':
                return [MonthlyPaymentStatsWidget::class];
            default: // For 'All' tab and other cases
                return [PaymentStats::class];
        }
    }

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

    protected function getFooterWidgets(): array {
        return [PaymentRemindersWidget::class];
    }
}
