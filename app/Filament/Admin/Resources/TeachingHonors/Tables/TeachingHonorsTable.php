<?php

namespace App\Filament\Admin\Resources\TeachingHonors\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TeachingHonorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('lecturer.id')
                    ->label('Dosen')
                    ->searchable(),
                TextColumn::make('classSession.id')
                    ->label('Sesi Kelas')
                    ->searchable(),
                TextColumn::make('payroll_period_id')
                    ->label('Periode Payroll')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('rate_per_sks')
                    ->label('Rate per SKS')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('sks')
                    ->label('SKS')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('meeting_count')
                    ->label('Jumlah Pertemuan')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('amount')
                    ->label('Jumlah')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
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
