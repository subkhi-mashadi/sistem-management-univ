<?php

namespace App\Filament\Admin\Resources\Lecturers\Pages;

use App\Filament\Admin\Resources\Lecturers\LecturerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLecturers extends ListRecords
{
    protected static string $resource = LecturerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
