<?php

namespace App\Filament\Admin\Resources\GradeSchemas\Pages;

use App\Filament\Admin\Resources\GradeSchemas\GradeSchemaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGradeSchemas extends ListRecords
{
    protected static string $resource = GradeSchemaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
