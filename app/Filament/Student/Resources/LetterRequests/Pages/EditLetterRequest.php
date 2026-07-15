<?php

namespace App\Filament\Student\Resources\LetterRequests\Pages;

use App\Filament\Student\Resources\LetterRequests\LetterRequestResource;
use Filament\Resources\Pages\EditRecord;

class EditLetterRequest extends EditRecord
{
    protected static string $resource = LetterRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
