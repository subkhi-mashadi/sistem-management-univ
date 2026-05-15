<?php

namespace App\Filament\Admin\Resources\ThesisTopics\Pages;

use App\Filament\Admin\Resources\ThesisTopics\ThesisTopicResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditThesisTopic extends EditRecord
{
    protected static string $resource = ThesisTopicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
