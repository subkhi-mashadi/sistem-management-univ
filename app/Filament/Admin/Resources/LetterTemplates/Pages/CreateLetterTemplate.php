<?php

namespace App\Filament\Admin\Resources\LetterTemplates\Pages;

use App\Filament\Admin\Resources\LetterTemplates\LetterTemplateResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLetterTemplate extends CreateRecord
{
    protected static string $resource = LetterTemplateResource::class;
}
