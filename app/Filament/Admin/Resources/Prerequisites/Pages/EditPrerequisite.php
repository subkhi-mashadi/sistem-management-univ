<?php

namespace App\Filament\Admin\Resources\Prerequisites\Pages;

use App\Filament\Admin\Resources\Prerequisites\PrerequisiteResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPrerequisite extends EditRecord
{
    protected static string $resource = PrerequisiteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
