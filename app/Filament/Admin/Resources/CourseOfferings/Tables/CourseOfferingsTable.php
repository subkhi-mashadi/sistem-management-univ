<?php

namespace App\Filament\Admin\Resources\CourseOfferings\Tables;

use App\Models\Lecturer;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class CourseOfferingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('course.name')
                    ->label('Mata Kuliah')
                    ->searchable(),
                TextColumn::make('semester.name')
                    ->label('Semester')
                    ->searchable(),
                TextColumn::make('class_code')
                    ->label('Kelas')
                    ->searchable(),
                TextColumn::make('lecturer_ids')
                    ->label('Dosen Pengampu')
                    ->getStateUsing(function ($record): string {
                        $ids = $record->lecturer_ids ?? [];
                        if (empty($ids)) {
                            return '—';
                        }

                        return Lecturer::whereIn('id', $ids)
                            ->with('user:id,full_name,name')
                            ->get()
                            ->map(fn (Lecturer $l) => $l->user?->full_name ?? $l->user?->name ?? "#{$l->id}")
                            ->join(', ');
                    })
                    ->wrap(),
                TextColumn::make('quota')
                    ->label('Kuota')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('enrolled_count')
                    ->label('Terdaftar')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_open')
                    ->label('Dibuka')
                    ->boolean(),
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
