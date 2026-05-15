<?php

namespace App\Filament\Admin\Resources\BillingComponents\Pages;

use App\Filament\Admin\Resources\BillingComponents\BillingComponentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBillingComponent extends CreateRecord
{
    protected static string $resource = BillingComponentResource::class;
}
