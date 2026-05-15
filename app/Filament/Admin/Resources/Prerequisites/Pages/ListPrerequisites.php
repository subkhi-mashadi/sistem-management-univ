<?php

namespace App\Filament\Admin\Resources\Prerequisites\Pages;

use App\Filament\Admin\Resources\Prerequisites\PrerequisiteResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPrerequisites extends ListRecords
{
    protected static string $resource = PrerequisiteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
