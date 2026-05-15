<?php

namespace App\Filament\Admin\Resources\ErpNotifications\Pages;

use App\Filament\Admin\Resources\ErpNotifications\ErpNotificationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListErpNotifications extends ListRecords
{
    protected static string $resource = ErpNotificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
