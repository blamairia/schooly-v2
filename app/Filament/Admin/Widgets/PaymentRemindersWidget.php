<?php

namespace App\Filament\Widgets;

use App\Filament\Admin\Resources\PaymentResource;
use App\Models\Payment;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class PaymentRemindersWidget extends BaseWidget
{
    protected function getCards(): array
    {
        $duePaymentsCount = Payment::whereIn('status', ['unpaid', 'partial'])
            ->whereBetween('due_date', [now(), now()->addDays(15)])
            ->count();

        return [
            Card::make('Payments Due Soon', $duePaymentsCount)
                ->description('Due in next 15 days')
                ->descriptionIcon('heroicon-o-clock')
                ->color('danger')
                ->url(PaymentResource::getUrl('index')),
        ];
    }
}
