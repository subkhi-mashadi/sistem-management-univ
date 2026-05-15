<?php

namespace App\Filament\Admin\Resources\ThesisAdvisors\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ThesisAdvisorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('thesisTopic.title')
                    ->label('Topik Skripsi')
                    ->searchable(),
                TextColumn::make('lecturer.id')
                    ->label('Dosen')
                    ->searchable(),
                TextColumn::make('advisor_order')
                    ->label('Urutan Pembimbing')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('assigned_at')
                    ->label('Ditugaskan')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
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
