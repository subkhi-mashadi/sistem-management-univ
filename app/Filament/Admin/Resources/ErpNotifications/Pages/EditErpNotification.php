<?php

namespace App\Filament\Admin\Resources\ErpNotifications\Pages;

use App\Filament\Admin\Resources\ErpNotifications\ErpNotificationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditErpNotification extends EditRecord
{
    protected static string $resource = ErpNotificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
