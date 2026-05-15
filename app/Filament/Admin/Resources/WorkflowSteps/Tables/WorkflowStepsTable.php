<?php

namespace App\Filament\Admin\Resources\WorkflowSteps\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WorkflowStepsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('workflow.name')
                    ->label('Workflow')
                    ->searchable(),
                TextColumn::make('step_order')
                    ->label('Urutan')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                TextColumn::make('approver_role_id')
                    ->label('Role Approver')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('approverUser.name')
                    ->label('User Approver')
                    ->searchable(),
                IconColumn::make('is_parallel')
                    ->label('Paralel')
                    ->boolean(),
                IconColumn::make('can_reject')
                    ->label('Bisa Tolak')
                    ->boolean(),
                TextColumn::make('sla_hours')
                    ->label('SLA (Jam)')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
