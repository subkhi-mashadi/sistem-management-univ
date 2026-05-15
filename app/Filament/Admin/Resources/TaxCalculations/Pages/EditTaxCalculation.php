<?php

namespace App\Filament\Admin\Resources\TaxCalculations\Pages;

use App\Filament\Admin\Resources\TaxCalculations\TaxCalculationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTaxCalculation extends EditRecord
{
    protected static string $resource = TaxCalculationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
