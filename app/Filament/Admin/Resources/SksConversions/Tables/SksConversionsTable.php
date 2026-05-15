<?php

namespace App\Filament\Admin\Resources\SksConversions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SksConversionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('mbkm_enrollment_id')
                    ->label('Pendaftaran MBKM')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('course.name')
                    ->label('Mata Kuliah')
                    ->searchable(),
                TextColumn::make('sks_converted')
                    ->label('SKS Konversi')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('letter_grade')
                    ->label('Huruf Mutu')
                    ->searchable(),
                TextColumn::make('grade_point')
                    ->label('Bobot')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('approved_by')
                    ->label('Disetujui Oleh')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('approved_at')
                    ->label('Tanggal Disetujui')
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
