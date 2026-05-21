<?php

namespace App\Filament\Admin\Resources\UktGroups\Pages;

use App\Filament\Admin\Resources\UktGroups\UktGroupResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUktGroups extends ListRecords
{
    protected static string $resource = UktGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
