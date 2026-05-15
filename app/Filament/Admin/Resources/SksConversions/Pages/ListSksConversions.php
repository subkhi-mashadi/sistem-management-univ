<?php

namespace App\Filament\Admin\Resources\SksConversions\Pages;

use App\Filament\Admin\Resources\SksConversions\SksConversionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSksConversions extends ListRecords
{
    protected static string $resource = SksConversionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
