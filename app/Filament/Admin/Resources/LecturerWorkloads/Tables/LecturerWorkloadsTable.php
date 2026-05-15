<?php

namespace App\Filament\Admin\Resources\LecturerWorkloads\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LecturerWorkloadsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('lecturer.id')
                    ->label('Dosen')
                    ->searchable(),
                TextColumn::make('semester.name')
                    ->label('Semester')
                    ->searchable(),
                TextColumn::make('teaching_sks')
                    ->label('SKS Mengajar')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('advisory_sks')
                    ->label('SKS Pembimbing')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('research_sks')
                    ->label('SKS Penelitian')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('service_sks')
                    ->label('SKS Pengabdian')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('additional_sks')
                    ->label('SKS Tambahan')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_sks')
                    ->label('Total SKS')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_locked')
                    ->label('Terkunci')
                    ->boolean(),
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
