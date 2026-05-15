<?php

namespace App\Filament\Admin\Resources\BillingRates\Pages;

use App\Filament\Admin\Resources\BillingRates\BillingRateResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBillingRate extends EditRecord
{
    protected static string $resource = BillingRateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
