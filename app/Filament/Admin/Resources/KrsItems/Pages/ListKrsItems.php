<?php

namespace App\Filament\Admin\Resources\KrsItems\Pages;

use App\Filament\Admin\Resources\KrsItems\KrsItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKrsItems extends ListRecords
{
    protected static string $resource = KrsItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
