<?php

namespace App\Filament\Admin\Resources\TaxCalculations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TaxCalculationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('period.name')
                    ->label('Periode Payroll')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('employee.user.name')
                    ->label('Pegawai')
                    ->searchable(),
                TextColumn::make('tax_year')
                    ->label('Tahun Pajak'),
                TextColumn::make('gross_year_to_date')
                    ->label('Bruto YTD')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ptkp_category')
                    ->label('Kategori PTKP')
                    ->searchable(),
                TextColumn::make('ptkp_amount')
                    ->label('Jumlah PTKP')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('taxable_income')
                    ->label('Penghasilan Kena Pajak')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tax_pph21')
                    ->label('PPh 21')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tax_pph21_ytd')
                    ->label('PPh 21 YTD')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_final')
                    ->label('Final')
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
