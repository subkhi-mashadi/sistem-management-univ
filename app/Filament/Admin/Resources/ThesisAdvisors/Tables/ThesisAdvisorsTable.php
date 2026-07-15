<?php

namespace App\Filament\Admin\Resources\ThesisAdvisors\Tables;

use App\Enums\Thesis\ThesisAdvisorStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ThesisAdvisorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('thesisTopic.student.user.name')
                    ->label('Mahasiswa')
                    ->searchable(),
                TextColumn::make('thesisTopic.title')
                    ->label('Judul Skripsi')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('lecturer.user.name')
                    ->label('Dosen Pembimbing')
                    ->searchable(),
                TextColumn::make('assigned_at')
                    ->label('Ditugaskan')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state instanceof ThesisAdvisorStatus ? $state->value : $state) {
                        'Active' => 'success',
                        'Completed' => 'info',
                        'Replaced' => 'gray',
                        default => 'gray',
                    }),
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
                SelectFilter::make('status')
                    ->label('Status Bimbingan')
                    ->options(ThesisAdvisorStatus::class),
            ])
            ->recordActions([
                EditAction::make()->label('Ubah Status'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
