<?php

namespace App\Filament\Admin\Resources\DeliveryLogs\Pages;

use App\Filament\Admin\Resources\DeliveryLogs\DeliveryLogResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDeliveryLog extends EditRecord
{
    protected static string $resource = DeliveryLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
