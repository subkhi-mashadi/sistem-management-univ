<?php

namespace App\Filament\Admin\Resources\BillingRates\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BillingRatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('billing_component_id')
                    ->label('Komponen Tagihan')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('studyProgram.name')
                    ->label('Program Studi')
                    ->searchable(),
                TextColumn::make('enrollment_year')
                    ->label('Tahun Masuk'),
                TextColumn::make('ukt_group')
                    ->label('Golongan UKT')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('amount')
                    ->label('Jumlah')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('effective_from')
                    ->label('Berlaku Dari')
                    ->date()
                    ->sortable(),
                TextColumn::make('effective_to')
                    ->label('Berlaku Hingga')
                    ->date()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Aktif')
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
