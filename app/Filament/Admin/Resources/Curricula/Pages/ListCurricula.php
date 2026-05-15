<?php

namespace App\Filament\Admin\Resources\Curricula\Pages;

use App\Filament\Admin\Resources\Curricula\CurriculumResource;
use App\Filament\Imports\CurriculumImporter;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListCurricula extends ListRecords
{
    protected static string $resource = CurriculumResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ImportAction::make()
                ->label('Import Excel')
                ->importer(CurriculumImporter::class),
        ];
    }
}
