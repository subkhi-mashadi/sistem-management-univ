<?php

namespace App\Filament\Admin\Resources\AttendanceRecords\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AttendanceRecordsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.user.name')
                    ->label('Pegawai')
                    ->searchable(),
                TextColumn::make('work_date')
                    ->label('Tanggal Kerja')
                    ->date()
                    ->sortable(),
                TextColumn::make('check_in_at')
                    ->label('Waktu Absen')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('check_out_at')
                    ->label('Jam Pulang')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('check_in_method')
                    ->label('Metode Masuk')
                    ->badge(),
                TextColumn::make('check_out_method')
                    ->label('Metode Pulang')
                    ->badge(),
                TextColumn::make('late_minutes')
                    ->label('Terlambat (Menit)')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('early_leave_minutes')
                    ->label('Pulang Cepat (Menit)')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
                TextColumn::make('notes')
                    ->label('Catatan')
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
