<?php

namespace App\Filament\Admin\Resources\DeliveryLogs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DeliveryLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('erp_notification_id')
                    ->label('Notifikasi')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('announcement.title')
                    ->label('Pengumuman')
                    ->searchable(),
                TextColumn::make('recipient.name')
                    ->label('Penerima')
                    ->searchable(),
                TextColumn::make('channel')
                    ->label('Channel')
                    ->badge(),
                TextColumn::make('provider')
                    ->label('Provider')
                    ->searchable(),
                TextColumn::make('provider_message_id')
                    ->label('ID Pesan Provider')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
                TextColumn::make('attempts')
                    ->label('Percobaan')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('sent_at')
                    ->label('Dikirim')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('delivered_at')
                    ->label('Diterima')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('read_at')
                    ->label('Dibaca Pada')
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
