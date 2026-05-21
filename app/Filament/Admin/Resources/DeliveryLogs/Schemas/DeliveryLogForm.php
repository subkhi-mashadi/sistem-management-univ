<?php

namespace App\Filament\Admin\Resources\DeliveryLogs\Schemas;

use App\Enums\Communication\DeliveryStatus;
use App\Enums\Communication\NotificationChannel;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DeliveryLogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama')
                    ->columnSpanFull()
                    ->columns(2)->components([
                        Select::make('erp_notification_id')
                            ->label('Notifikasi')
                            ->relationship('notification', 'title')->searchable()->preload(),
                        Select::make('announcement_id')
                            ->label('Pengumuman')
                            ->relationship('announcement', 'title'),
                        Select::make('recipient_id')
                            ->label('Penerima')
                            ->relationship('recipient', 'name')
                            ->required(),
                        Select::make('channel')
                            ->label('Channel')
                            ->options(NotificationChannel::class)
                            ->required(),
                        TextInput::make('provider')
                            ->label('Provider'),
                        TextInput::make('provider_message_id')
                            ->label('ID Pesan Provider'),
                        Select::make('status')
                            ->label('Status')
                            ->options(DeliveryStatus::class)
                            ->default('Queued')
                            ->required(),
                        Textarea::make('error_message')
                            ->label('Pesan Error')
                            ->columnSpanFull(),
                        TextInput::make('attempts')
                            ->label('Percobaan')
                            ->required()
                            ->numeric()
                            ->default(0),
                        DateTimePicker::make('sent_at')
                            ->label('Dikirim'),
                        DateTimePicker::make('delivered_at')
                            ->label('Diterima'),
                        DateTimePicker::make('read_at')
                            ->label('Dibaca Pada'),
                    ]),
            ]);
    }
}
