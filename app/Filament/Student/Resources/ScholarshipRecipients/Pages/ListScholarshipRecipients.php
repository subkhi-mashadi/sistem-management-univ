<?php

namespace App\Filament\Student\Resources\ScholarshipRecipients\Pages;

use App\Filament\Student\Resources\ScholarshipRecipients\ScholarshipRecipientResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListScholarshipRecipients extends ListRecords
{
    protected static string $resource = ScholarshipRecipientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Ajukan Beasiswa'),
        ];
    }
}
