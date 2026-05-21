<?php

namespace App\Filament\Admin\Resources\GradeAppeals\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GradeAppealsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('grade.courseOffering.course.code')
                    ->label('Nilai')
                    ->searchable(),
                TextColumn::make('student.user.name')
                    ->label('Mahasiswa')
                    ->searchable(),
                TextColumn::make('original_letter')
                    ->label('Huruf Asal')
                    ->searchable(),
                TextColumn::make('revised_letter')
                    ->label('Huruf Revisi')
                    ->searchable(),
                TextColumn::make('original_score')
                    ->label('Nilai Asal')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('revised_score')
                    ->label('Nilai Revisi')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
                TextColumn::make('reviewed_by')
                    ->label('Direview Oleh')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('reviewed_at')
                    ->label('Tanggal Review')
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
