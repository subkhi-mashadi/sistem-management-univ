<?php

namespace App\Filament\Admin\Resources\Semesters\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SemestersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('academicCalendar.name')
                    ->label('Kalender Akademik')
                    ->searchable(),
                TextColumn::make('code')
                    ->label('Kode')
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                TextColumn::make('term')
                    ->label('Term')
                    ->badge(),
                TextColumn::make('start_date')
                    ->label('Tanggal Mulai')
                    ->date()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->label('Tanggal Selesai')
                    ->date()
                    ->sortable(),
                TextColumn::make('krs_start')
                    ->label('Mulai KRS')
                    ->date()
                    ->sortable(),
                TextColumn::make('krs_end')
                    ->label('Akhir KRS')
                    ->date()
                    ->sortable(),
                TextColumn::make('lecture_start')
                    ->label('Mulai Kuliah')
                    ->date()
                    ->sortable(),
                TextColumn::make('lecture_end')
                    ->label('Akhir Kuliah')
                    ->date()
                    ->sortable(),
                TextColumn::make('uts_start')
                    ->label('Mulai UTS')
                    ->date()
                    ->sortable(),
                TextColumn::make('uts_end')
                    ->label('Akhir UTS')
                    ->date()
                    ->sortable(),
                TextColumn::make('uas_start')
                    ->label('Mulai UAS')
                    ->date()
                    ->sortable(),
                TextColumn::make('uas_end')
                    ->label('Akhir UAS')
                    ->date()
                    ->sortable(),
                TextColumn::make('grade_input_deadline')
                    ->label('Batas Input Nilai')
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
