<?php

namespace App\Filament\Admin\Resources\ScholarshipRecipients\Pages;

use App\Filament\Admin\Resources\ScholarshipRecipients\ScholarshipRecipientResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListScholarshipRecipients extends ListRecords
{
    protected static string $resource = ScholarshipRecipientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
