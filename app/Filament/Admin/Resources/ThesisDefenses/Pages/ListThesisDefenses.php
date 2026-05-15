<?php

namespace App\Filament\Admin\Resources\ThesisDefenses\Pages;

use App\Filament\Admin\Resources\ThesisDefenses\ThesisDefenseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListThesisDefenses extends ListRecords
{
    protected static string $resource = ThesisDefenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
