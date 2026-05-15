<?php

namespace App\Filament\Admin\Resources\PayrollPeriods\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PayrollPeriodsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Kode')
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                TextColumn::make('month')
                    ->label('Bulan')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('year')
                    ->label('Tahun'),
                TextColumn::make('period_start')
                    ->label('Mulai Periode')
                    ->date()
                    ->sortable(),
                TextColumn::make('period_end')
                    ->label('Akhir Periode')
                    ->date()
                    ->sortable(),
                TextColumn::make('cutoff_date')
                    ->label('Tanggal Cutoff')
                    ->date()
                    ->sortable(),
                TextColumn::make('payment_date')
                    ->label('Tanggal Bayar')
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
                TextColumn::make('closed_at')
                    ->label('Ditutup')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('closed_by')
                    ->label('Ditutup Oleh')
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
