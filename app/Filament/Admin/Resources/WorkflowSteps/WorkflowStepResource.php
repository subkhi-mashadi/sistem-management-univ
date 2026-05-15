<?php

namespace App\Filament\Admin\Resources\WorkflowSteps;

use App\Filament\Admin\Resources\WorkflowSteps\Pages\CreateWorkflowStep;
use App\Filament\Admin\Resources\WorkflowSteps\Pages\EditWorkflowStep;
use App\Filament\Admin\Resources\WorkflowSteps\Pages\ListWorkflowSteps;
use App\Filament\Admin\Resources\WorkflowSteps\Schemas\WorkflowStepForm;
use App\Filament\Admin\Resources\WorkflowSteps\Tables\WorkflowStepsTable;
use App\Models\WorkflowStep;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WorkflowStepResource extends Resource
{
    protected static ?string $model = WorkflowStep::class;

    protected static string|\UnitEnum|null $navigationGroup = 'E-Office';

    protected static ?string $navigationLabel = 'Step Workflow';

    protected static ?string $modelLabel = 'Step Workflow';

    protected static ?string $pluralModelLabel = 'Step Workflow';

    protected static ?int $navigationSort = 62;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQueueList;

    public static function form(Schema $schema): Schema
    {
        return WorkflowStepForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WorkflowStepsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWorkflowSteps::route('/'),
            'create' => CreateWorkflowStep::route('/create'),
            'edit' => EditWorkflowStep::route('/{record}/edit'),
        ];
    }
}
