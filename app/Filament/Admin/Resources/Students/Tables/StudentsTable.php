<?php

namespace App\Filament\Admin\Resources\Students\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class StudentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Pengguna')
                    ->searchable(),
                TextColumn::make('nim')
                    ->label('NIM')
                    ->searchable(),
                TextColumn::make('studyProgram.name')
                    ->label('Program Studi')
                    ->searchable(),
                TextColumn::make('curriculum.code')
                    ->label('Kurikulum')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('enrollment_year')
                    ->label('Tahun Masuk'),
                TextColumn::make('advisor.user.name')
                    ->label('Dosen Wali')
                    ->searchable()
                    ->sortable()
                    ->placeholder('—'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
                TextColumn::make('entry_path')
                    ->label('Jalur Masuk')
                    ->badge(),
                TextColumn::make('ukt_group')
                    ->label('Golongan UKT')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('gender')
                    ->label('Jenis Kelamin')
                    ->badge(),
                TextColumn::make('birth_place')
                    ->label('Tempat Lahir')
                    ->searchable(),
                TextColumn::make('birth_date')
                    ->label('Tanggal Lahir')
                    ->date()
                    ->sortable(),
                TextColumn::make('phone')
                    ->label('Telepon')
                    ->searchable(),
                TextColumn::make('parent_name')
                    ->label('Nama Wali')
                    ->searchable(),
                TextColumn::make('parent_phone')
                    ->label('Telepon Wali')
                    ->searchable(),
                TextColumn::make('religion')
                    ->label('Agama')
                    ->searchable(),
                TextColumn::make('nationality')
                    ->label('Kewarganegaraan')
                    ->searchable(),
                TextColumn::make('deleted_at')
                    ->label('Dihapus')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
