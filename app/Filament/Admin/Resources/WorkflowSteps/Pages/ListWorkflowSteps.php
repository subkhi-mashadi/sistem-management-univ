<?php

namespace App\Filament\Admin\Resources\WorkflowSteps\Pages;

use App\Filament\Admin\Resources\WorkflowSteps\WorkflowStepResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWorkflowSteps extends ListRecords
{
    protected static string $resource = WorkflowStepResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
