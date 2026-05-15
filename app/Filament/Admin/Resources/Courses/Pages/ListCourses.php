<?php

namespace App\Filament\Admin\Resources\Courses\Pages;

use App\Filament\Admin\Resources\Courses\CourseResource;
use App\Filament\Imports\CourseImporter;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListCourses extends ListRecords
{
    protected static string $resource = CourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ImportAction::make()
                ->label('Import Excel')
                ->importer(CourseImporter::class),
        ];
    }
}
