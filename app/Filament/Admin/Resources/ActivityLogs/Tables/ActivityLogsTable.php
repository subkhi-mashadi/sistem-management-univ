<?php

namespace App\Filament\Admin\Resources\ActivityLogs\Tables;

use Filament\Actions\ViewAction;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Models\Activity;

class ActivityLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->since()
                    ->sortable(),
                TextColumn::make('log_name')
                    ->label('Log')
                    ->badge()
                    ->sortable(),
                TextColumn::make('event')
                    ->label('Event')
                    ->badge()
                    ->colors([
                        'success' => 'created',
                        'warning' => 'updated',
                        'danger' => 'deleted',
                    ]),
                TextColumn::make('causer.full_name')
                    ->label('User')
                    ->default(fn ($record) => $record->causer?->name ?? '-')
                    ->searchable(),
                TextColumn::make('description')
                    ->label('Deskripsi')
                    ->wrap()
                    ->limit(80),
                TextColumn::make('subject_type')
                    ->label('Tipe Entitas')
                    ->formatStateUsing(fn (?string $state) => $state ? class_basename($state) : '-')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('subject_id')
                    ->label('Subject #'),
            ])
            ->filters([
                SelectFilter::make('log_name')
                    ->options(fn () => Activity::query()
                        ->distinct()
                        ->whereNotNull('log_name')
                        ->pluck('log_name', 'log_name')
                        ->all()),
                SelectFilter::make('event')
                    ->options([
                        'created' => 'Created',
                        'updated' => 'Updated',
                        'deleted' => 'Deleted',
                    ]),
                Filter::make('today')
                    ->label('Hari ini')
                    ->query(fn (Builder $q) => $q->whereDate('created_at', today())),
            ])
            ->recordActions([
                ViewAction::make()
                    ->schema(fn (Schema $schema) => $schema->components([
                        Section::make('Activity')
                            ->columns(2)
                            ->components([
                                TextEntry::make('created_at')->dateTime(),
                                TextEntry::make('log_name'),
                                TextEntry::make('event'),
                                TextEntry::make('description'),
                                TextEntry::make('causer.full_name')
                                    ->label('User')
                                    ->default(fn ($record) => $record->causer?->name ?? '-'),
                                TextEntry::make('subject_type')
                                    ->formatStateUsing(fn (?string $state) => $state ? class_basename($state) : '-'),
                            ]),
                        Section::make('Changes')
                            ->components([
                                KeyValueEntry::make('properties.attributes')
                                    ->label('After')
                                    ->state(fn ($record) => $record->properties['attributes'] ?? []),
                                KeyValueEntry::make('properties.old')
                                    ->label('Before')
                                    ->state(fn ($record) => $record->properties['old'] ?? []),
                            ]),
                    ])),
            ])
            ->toolbarActions([]);
    }
}
