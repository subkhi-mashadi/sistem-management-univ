<?php

namespace App\Filament\Admin\Resources\WorkflowSteps\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class WorkflowStepForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('workflow_id')
                    ->label('Workflow')
                    ->relationship('workflow', 'name')
                    ->required(),
                TextInput::make('step_order')
                    ->label('Urutan')
                    ->required()
                    ->numeric(),
                TextInput::make('name')
                    ->label('Nama')
                    ->required(),
                TextInput::make('approver_role_id')
                    ->label('Role Approver')
                    ->numeric(),
                Select::make('approver_user_id')
                    ->label('User Approver')
                    ->relationship('approverUser', 'name'),
                Toggle::make('is_parallel')
                    ->label('Paralel')
                    ->required(),
                Toggle::make('can_reject')
                    ->label('Bisa Tolak')
                    ->required(),
                TextInput::make('sla_hours')
                    ->label('SLA (Jam)')
                    ->required()
                    ->numeric()
                    ->default(72),
            ]);
    }
}
