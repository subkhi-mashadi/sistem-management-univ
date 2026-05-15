<?php

namespace App\Filament\Admin\Resources\Classrooms\Pages;

use App\Filament\Admin\Resources\Classrooms\ClassroomResource;
use App\Filament\Imports\ClassroomImporter;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListClassrooms extends ListRecords
{
    protected static string $resource = ClassroomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ImportAction::make()
                ->label('Import Excel')
                ->importer(ClassroomImporter::class),
        ];
    }
}
