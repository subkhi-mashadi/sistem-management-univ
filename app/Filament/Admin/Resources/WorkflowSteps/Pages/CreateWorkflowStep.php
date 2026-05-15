<?php

namespace App\Filament\Admin\Resources\WorkflowSteps\Pages;

use App\Filament\Admin\Resources\WorkflowSteps\WorkflowStepResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWorkflowStep extends CreateRecord
{
    protected static string $resource = WorkflowStepResource::class;
}
