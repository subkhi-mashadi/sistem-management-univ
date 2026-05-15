<?php

namespace App\Filament\Admin\Resources\StudyPrograms\Pages;

use App\Filament\Admin\Resources\StudyPrograms\StudyProgramResource;
use App\Filament\Imports\StudyProgramImporter;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListStudyPrograms extends ListRecords
{
    protected static string $resource = StudyProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ImportAction::make()
                ->label('Import Excel')
                ->importer(StudyProgramImporter::class),
        ];
    }
}
