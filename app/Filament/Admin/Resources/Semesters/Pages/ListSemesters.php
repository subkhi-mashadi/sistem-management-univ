<?php

namespace App\Filament\Admin\Resources\Semesters\Pages;

use App\Filament\Admin\Resources\Semesters\SemesterResource;
use App\Filament\Imports\SemesterImporter;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListSemesters extends ListRecords
{
    protected static string $resource = SemesterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ImportAction::make()
                ->label('Import Excel')
                ->importer(SemesterImporter::class),
        ];
    }
}
