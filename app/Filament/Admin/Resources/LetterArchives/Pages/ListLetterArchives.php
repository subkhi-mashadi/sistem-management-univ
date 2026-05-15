<?php

namespace App\Filament\Admin\Resources\LetterArchives\Pages;

use App\Filament\Admin\Resources\LetterArchives\LetterArchiveResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLetterArchives extends ListRecords
{
    protected static string $resource = LetterArchiveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
