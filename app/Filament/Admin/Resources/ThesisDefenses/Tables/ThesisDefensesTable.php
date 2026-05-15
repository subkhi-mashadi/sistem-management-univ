<?php

namespace App\Filament\Admin\Resources\ThesisDefenses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ThesisDefensesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('thesisTopic.title')
                    ->label('Topik Skripsi')
                    ->searchable(),
                TextColumn::make('defense_type')
                    ->label('Jenis Sidang')
                    ->badge(),
                TextColumn::make('scheduled_at')
                    ->label('Dijadwalkan')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('room')
                    ->label('Ruangan')
                    ->searchable(),
                TextColumn::make('plagiarism_score')
                    ->label('Skor Plagiarisme')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('final_score')
                    ->label('Nilai UAS')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('letter_grade')
                    ->label('Huruf Mutu')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
                TextColumn::make('deleted_at')
                    ->label('Dihapus')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
