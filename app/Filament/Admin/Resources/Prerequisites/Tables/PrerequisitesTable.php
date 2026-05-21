<?php

namespace App\Filament\Admin\Resources\Prerequisites\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PrerequisitesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('course.name')
                    ->label('Mata Kuliah')
                    ->searchable(),
                TextColumn::make('prerequisiteCourse.name')
                    ->label('MK Prasyarat')
                    ->searchable(),
                TextColumn::make('minimum_grade')
                    ->label('Nilai Minimum')
                    ->badge()
                    ->searchable(),
                TextColumn::make('group_no')
                    ->label('Grup')
                    ->formatStateUsing(fn ($state) => 'Grup '.$state)
                    ->badge()
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
