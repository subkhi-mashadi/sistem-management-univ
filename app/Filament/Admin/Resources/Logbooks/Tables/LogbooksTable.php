<?php

namespace App\Filament\Admin\Resources\Logbooks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LogbooksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('thesisTopic.title')
                    ->label('Topik Skripsi')
                    ->searchable(),
                TextColumn::make('student.user.name')
                    ->label('Mahasiswa')
                    ->searchable(),
                TextColumn::make('lecturer.user.name')
                    ->label('Dosen')
                    ->searchable(),
                TextColumn::make('session_date')
                    ->label('Tanggal Sesi')
                    ->date()
                    ->sortable(),
                TextColumn::make('duration_minutes')
                    ->label('Durasi (Menit)')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_verified')
                    ->label('Terverifikasi')
                    ->boolean(),
                TextColumn::make('verified_at')
                    ->label('Diverifikasi')
                    ->dateTime()
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
