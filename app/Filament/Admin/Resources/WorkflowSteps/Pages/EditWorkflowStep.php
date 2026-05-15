<?php

namespace App\Filament\Admin\Resources\WorkflowSteps\Pages;

use App\Filament\Admin\Resources\WorkflowSteps\WorkflowStepResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWorkflowStep extends EditRecord
{
    protected static string $resource = WorkflowStepResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
