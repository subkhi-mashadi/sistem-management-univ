<?php

namespace App\Filament\Admin\Resources\Approvals\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ApprovalsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('letterRequest.id')
                    ->label('Pengajuan Surat')
                    ->searchable(),
                TextColumn::make('workflow_step_id')
                    ->label('Step Workflow')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('approver.name')
                    ->label('Approver')
                    ->searchable(),
                TextColumn::make('action')
                    ->label('Aksi')
                    ->badge(),
                TextColumn::make('acted_at')
                    ->label('Waktu Aksi')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('delegated_to')
                    ->label('Didelegasi Ke')
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
