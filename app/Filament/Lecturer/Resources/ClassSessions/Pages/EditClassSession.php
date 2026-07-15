<?php

namespace App\Filament\Lecturer\Resources\ClassSessions\Pages;

use App\Filament\Lecturer\Resources\ClassSessions\ClassSessionResource;
use Filament\Resources\Pages\EditRecord;

class EditClassSession extends EditRecord
{
    protected static string $resource = ClassSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
