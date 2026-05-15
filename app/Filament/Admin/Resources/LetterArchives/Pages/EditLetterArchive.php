<?php

namespace App\Filament\Admin\Resources\LetterArchives\Pages;

use App\Filament\Admin\Resources\LetterArchives\LetterArchiveResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLetterArchive extends EditRecord
{
    protected static string $resource = LetterArchiveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
