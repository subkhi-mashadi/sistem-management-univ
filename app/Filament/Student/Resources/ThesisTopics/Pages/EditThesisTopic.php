<?php

namespace App\Filament\Student\Resources\ThesisTopics\Pages;

use App\Filament\Student\Resources\ThesisTopics\ThesisTopicResource;
use Filament\Resources\Pages\EditRecord;

class EditThesisTopic extends EditRecord
{
    protected static string $resource = ThesisTopicResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
