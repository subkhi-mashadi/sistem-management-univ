<?php

namespace App\Filament\Admin\Resources\ResearchRepositories\Pages;

use App\Filament\Admin\Resources\ResearchRepositories\ResearchRepositoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListResearchRepositories extends ListRecords
{
    protected static string $resource = ResearchRepositoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
