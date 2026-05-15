<?php

namespace App\Filament\Admin\Resources\GradeSchemas\Pages;

use App\Filament\Admin\Resources\GradeSchemas\GradeSchemaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGradeSchema extends EditRecord
{
    protected static string $resource = GradeSchemaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
