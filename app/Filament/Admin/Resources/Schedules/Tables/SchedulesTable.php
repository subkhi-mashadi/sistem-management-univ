<?php

namespace App\Filament\Admin\Resources\Schedules\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SchedulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('courseOffering.course.code')
                    ->label('Kode MK')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('courseOffering.course.name')
                    ->label('Mata Kuliah')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('courseOffering.class_code')
                    ->label('Kelas')
                    ->badge()
                    ->sortable(),
                TextColumn::make('classroom.name')
                    ->label('Ruang Kelas')
                    ->searchable(),
                TextColumn::make('day_of_week')
                    ->label('Hari')
                    ->badge(),
                TextColumn::make('start_time')
                    ->label('Jam Mulai')
                    ->time()
                    ->sortable(),
                TextColumn::make('end_time')
                    ->label('Jam Selesai')
                    ->time()
                    ->sortable(),
                TextColumn::make('meeting_count')
                    ->label('Jumlah Pertemuan')
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
