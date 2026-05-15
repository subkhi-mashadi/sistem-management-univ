<?php

namespace App\Filament\Admin\Resources\DeliveryLogs\Pages;

use App\Filament\Admin\Resources\DeliveryLogs\DeliveryLogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDeliveryLogs extends ListRecords
{
    protected static string $resource = DeliveryLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
