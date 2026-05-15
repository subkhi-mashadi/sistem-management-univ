<?php

namespace App\Filament\Admin\Resources\MbkmEnrollments\Pages;

use App\Filament\Admin\Resources\MbkmEnrollments\MbkmEnrollmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMbkmEnrollments extends ListRecords
{
    protected static string $resource = MbkmEnrollmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
