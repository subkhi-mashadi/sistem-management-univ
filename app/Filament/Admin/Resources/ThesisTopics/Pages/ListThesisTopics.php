<?php

namespace App\Filament\Admin\Resources\ThesisTopics\Pages;

use App\Filament\Admin\Resources\ThesisTopics\ThesisTopicResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListThesisTopics extends ListRecords
{
    protected static string $resource = ThesisTopicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
