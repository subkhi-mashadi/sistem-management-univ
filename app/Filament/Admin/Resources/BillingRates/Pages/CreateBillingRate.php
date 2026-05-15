<?php

namespace App\Filament\Admin\Resources\BillingRates\Pages;

use App\Filament\Admin\Resources\BillingRates\BillingRateResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBillingRate extends CreateRecord
{
    protected static string $resource = BillingRateResource::class;
}
