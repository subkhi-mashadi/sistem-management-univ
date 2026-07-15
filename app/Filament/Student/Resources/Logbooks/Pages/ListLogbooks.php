<?php

namespace App\Filament\Student\Resources\Logbooks\Pages;

use App\Filament\Student\Resources\Logbooks\LogbookResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLogbooks extends ListRecords
{
    protected static string $resource = LogbookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Catat Bimbingan'),
        ];
    }
}
