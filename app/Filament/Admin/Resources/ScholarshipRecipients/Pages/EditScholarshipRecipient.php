<?php

namespace App\Filament\Admin\Resources\ScholarshipRecipients\Pages;

use App\Filament\Admin\Resources\ScholarshipRecipients\ScholarshipRecipientResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditScholarshipRecipient extends EditRecord
{
    protected static string $resource = ScholarshipRecipientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
