<?php

namespace App\Filament\Admin\Resources\ResearchRepositories\Pages;

use App\Filament\Admin\Resources\ResearchRepositories\ResearchRepositoryResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditResearchRepository extends EditRecord
{
    protected static string $resource = ResearchRepositoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
