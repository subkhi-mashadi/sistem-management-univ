<?php

namespace App\Filament\Admin\Resources\ErpNotifications\Schemas;

use App\Enums\Communication\NotificationChannel;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ErpNotificationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('recipient_id')
                    ->label('Penerima')
                    ->relationship('recipient', 'name')
                    ->required(),
                TextInput::make('template_code')
                    ->label('Kode Template'),
                Select::make('announcement_id')
                    ->label('Pengumuman')
                    ->relationship('announcement', 'title'),
                TextInput::make('title')
                    ->label('Judul')
                    ->required(),
                Textarea::make('body')
                    ->label('Isi')
                    ->required()
                    ->columnSpanFull(),
                Select::make('channel')
                    ->label('Channel')
                    ->options(NotificationChannel::class)
                    ->required(),
                Toggle::make('is_read')
                    ->label('Dibaca')
                    ->required(),
                DateTimePicker::make('read_at')
                    ->label('Dibaca Pada'),
                TextInput::make('payload')
                    ->label('Payload'),
            ]);
    }
}
