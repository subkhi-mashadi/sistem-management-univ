<?php

namespace App\Filament\Admin\Resources\Students\Pages;

use App\Filament\Admin\Resources\Students\StudentResource;
use App\Filament\Imports\StudentImporter;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListStudents extends ListRecords
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ImportAction::make()
                ->label('Import Excel')
                ->importer(StudentImporter::class),
        ];
    }
}
