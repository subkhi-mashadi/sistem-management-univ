<?php

namespace App\Filament\Admin\Resources\Workflows;

use App\Filament\Admin\Resources\Workflows\Pages\CreateWorkflow;
use App\Filament\Admin\Resources\Workflows\Pages\EditWorkflow;
use App\Filament\Admin\Resources\Workflows\Pages\ListWorkflows;
use App\Filament\Admin\Resources\Workflows\Schemas\WorkflowForm;
use App\Filament\Admin\Resources\Workflows\Tables\WorkflowsTable;
use App\Models\Workflow;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WorkflowResource extends Resource
{
    protected static ?string $model = Workflow::class;

    protected static string|\UnitEnum|null $navigationGroup = 'E-Office (Lanjutan)';

    protected static ?string $navigationLabel = 'Workflow';

    protected static ?string $modelLabel = 'Workflow';

    protected static ?string $pluralModelLabel = 'Workflow';

    protected static ?int $navigationSort = 61;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowsRightLeft;

    public static function form(Schema $schema): Schema
    {
        return WorkflowForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WorkflowsTable::configure($table);
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
            'index' => ListWorkflows::route('/'),
            'create' => CreateWorkflow::route('/create'),
            'edit' => EditWorkflow::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
