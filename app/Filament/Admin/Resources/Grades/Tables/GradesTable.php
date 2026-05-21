<?php

namespace App\Filament\Admin\Resources\Grades\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GradesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.user.name')
                    ->label('Mahasiswa')
                    ->searchable(),
                TextColumn::make('courseOffering.id')
                    ->label('Penawaran MK')
                    ->searchable(),
                TextColumn::make('krsItem.id')
                    ->label('Item KRS')
                    ->searchable(),
                TextColumn::make('attendance_score')
                    ->label('Nilai Kehadiran')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('assignment_score')
                    ->label('Nilai Tugas')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('mid_score')
                    ->label('Nilai UTS')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('final_score')
                    ->label('Nilai UAS')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('extra_score')
                    ->label('Nilai Tambahan')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_score')
                    ->label('Nilai Total')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('letter_grade')
                    ->label('Huruf Mutu')
                    ->searchable(),
                TextColumn::make('grade_point')
                    ->label('Bobot')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_locked')
                    ->label('Terkunci')
                    ->boolean(),
                TextColumn::make('locked_at')
                    ->label('Dikunci Pada')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('locked_by')
                    ->label('Dikunci Oleh')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('submitted_by')
                    ->label('Diinput Oleh')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('submitted_at')
                    ->label('Tanggal Input')
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
