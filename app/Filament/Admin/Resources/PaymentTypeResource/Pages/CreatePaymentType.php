<?php

namespace App\Filament\Admin\Resources\PaymentTypeResource\Pages;

use App\Filament\Admin\Resources\PaymentTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePaymentType extends CreateRecord
{
    protected static string $resource = PaymentTypeResource::class;
}
