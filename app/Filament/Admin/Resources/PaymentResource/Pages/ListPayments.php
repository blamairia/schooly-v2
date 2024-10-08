<?php

namespace App\Filament\Admin\Resources\PaymentResource\Pages;

use App\Filament\Admin\Resources\PaymentResource;
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
        ];
    }
    protected function getHeaderWidgets(): array {
        return [
            PaymentStats::class,
        ];
    }
    public function getTabs(): array {
        return [
            'All' => Tab::make(),
            'Today' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query)
                 =>$query->whereDate('created_at', now())
            ),
            'This Week' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query)
                 =>$query->whereBetween('created_at', [now()->startOfWeek(), now()])
            ),

            'this Month' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query)
                 =>$query->whereBetween('created_at', [now()->startOfMonth(), now()])
            ),
            'Paid' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query)
                 =>$query->where('amount_due', 0)
            ),

        ];
    }
}
