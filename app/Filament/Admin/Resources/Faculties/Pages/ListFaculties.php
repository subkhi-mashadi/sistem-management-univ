<?php

namespace App\Filament\Admin\Resources\Faculties\Pages;

use App\Filament\Admin\Resources\Faculties\FacultyResource;
use App\Filament\Imports\FacultyImporter;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListFaculties extends ListRecords
{
    protected static string $resource = FacultyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ImportAction::make()
                ->label('Import Excel')
                ->importer(FacultyImporter::class),
        ];
    }
}
