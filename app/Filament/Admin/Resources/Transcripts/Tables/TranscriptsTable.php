<?php

namespace App\Filament\Admin\Resources\Transcripts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TranscriptsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.id')
                    ->label('Mahasiswa')
                    ->searchable(),
                TextColumn::make('semester.name')
                    ->label('Semester')
                    ->searchable(),
                TextColumn::make('sks_attempted')
                    ->label('SKS Ditempuh')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('sks_acquired')
                    ->label('SKS Lulus')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('semester_gpa')
                    ->label('IPS')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('cumulative_gpa')
                    ->label('IPK')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('sks_cumulative')
                    ->label('SKS Kumulatif')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('academic_status')
                    ->label('Status Akademik')
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
