<?php

namespace App\Filament\Admin\Resources\Salaries\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SalariesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('payroll_period_id')
                    ->label('Periode Payroll')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('employee.id')
                    ->label('Pegawai')
                    ->searchable(),
                TextColumn::make('base_salary')
                    ->label('Gaji Pokok')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_allowance')
                    ->label('Total Tunjangan')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('teaching_honor')
                    ->label('Honor Mengajar')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('other_income')
                    ->label('Pendapatan Lain')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('gross_total')
                    ->label('Total Bruto')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('bpjs_deduction')
                    ->label('Potongan BPJS')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tax_pph21')
                    ->label('PPh 21')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('other_deduction')
                    ->label('Potongan Lain')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_deduction')
                    ->label('Total Potongan')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('net_total')
                    ->label('Total Netto')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
                TextColumn::make('paid_at')
                    ->label('Dibayar Pada')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('slip_pdf_url')
                    ->label('Slip PDF')
                    ->searchable(),
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
