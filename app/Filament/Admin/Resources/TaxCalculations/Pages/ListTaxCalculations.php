<?php

namespace App\Filament\Admin\Resources\TaxCalculations\Pages;

use App\Filament\Admin\Resources\TaxCalculations\TaxCalculationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTaxCalculations extends ListRecords
{
    protected static string $resource = TaxCalculationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
