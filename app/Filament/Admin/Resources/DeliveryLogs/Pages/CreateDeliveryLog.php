<?php

namespace App\Filament\Admin\Resources\DeliveryLogs\Pages;

use App\Filament\Admin\Resources\DeliveryLogs\DeliveryLogResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDeliveryLog extends CreateRecord
{
    protected static string $resource = DeliveryLogResource::class;
}
