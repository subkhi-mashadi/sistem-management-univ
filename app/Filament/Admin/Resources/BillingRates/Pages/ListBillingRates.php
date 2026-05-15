<?php

namespace App\Filament\Admin\Resources\BillingRates\Pages;

use App\Filament\Admin\Resources\BillingRates\BillingRateResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBillingRates extends ListRecords
{
    protected static string $resource = BillingRateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
