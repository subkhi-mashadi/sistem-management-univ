<?php

namespace App\Filament\Admin\Resources\BillingComponents\Pages;

use App\Filament\Admin\Resources\BillingComponents\BillingComponentResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditBillingComponent extends EditRecord
{
    protected static string $resource = BillingComponentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
