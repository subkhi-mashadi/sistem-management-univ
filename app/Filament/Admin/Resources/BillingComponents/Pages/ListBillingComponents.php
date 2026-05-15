<?php

namespace App\Filament\Admin\Resources\BillingComponents\Pages;

use App\Filament\Admin\Resources\BillingComponents\BillingComponentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBillingComponents extends ListRecords
{
    protected static string $resource = BillingComponentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
