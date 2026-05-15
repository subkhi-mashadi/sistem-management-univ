<?php

namespace App\Filament\Admin\Resources\LecturerWorkloads\Pages;

use App\Filament\Admin\Resources\LecturerWorkloads\LecturerWorkloadResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLecturerWorkloads extends ListRecords
{
    protected static string $resource = LecturerWorkloadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
