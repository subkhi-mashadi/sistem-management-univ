<?php

namespace App\Filament\Admin\Resources\MbkmPrograms\Pages;

use App\Filament\Admin\Resources\MbkmPrograms\MbkmProgramResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMbkmPrograms extends ListRecords
{
    protected static string $resource = MbkmProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
