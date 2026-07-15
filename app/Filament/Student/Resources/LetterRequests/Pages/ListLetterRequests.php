<?php

namespace App\Filament\Student\Resources\LetterRequests\Pages;

use App\Filament\Student\Resources\LetterRequests\LetterRequestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLetterRequests extends ListRecords
{
    protected static string $resource = LetterRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
