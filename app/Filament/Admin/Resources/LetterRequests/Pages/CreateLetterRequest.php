<?php

namespace App\Filament\Admin\Resources\LetterRequests\Pages;

use App\Filament\Admin\Resources\LetterRequests\LetterRequestResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLetterRequest extends CreateRecord
{
    protected static string $resource = LetterRequestResource::class;
}
