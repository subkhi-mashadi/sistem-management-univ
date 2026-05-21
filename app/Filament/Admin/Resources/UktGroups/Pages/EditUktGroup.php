<?php

namespace App\Filament\Admin\Resources\UktGroups\Pages;

use App\Filament\Admin\Resources\UktGroups\UktGroupResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUktGroup extends EditRecord
{
    protected static string $resource = UktGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
