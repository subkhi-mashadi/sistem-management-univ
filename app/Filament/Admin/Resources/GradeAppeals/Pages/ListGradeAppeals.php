<?php

namespace App\Filament\Admin\Resources\GradeAppeals\Pages;

use App\Filament\Admin\Resources\GradeAppeals\GradeAppealResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGradeAppeals extends ListRecords
{
    protected static string $resource = GradeAppealResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
