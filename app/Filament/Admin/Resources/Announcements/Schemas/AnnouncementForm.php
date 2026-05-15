<?php

namespace App\Filament\Admin\Resources\Announcements\Schemas;

use App\Enums\Communication\AnnouncementStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AnnouncementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Judul')
                    ->required(),
                Textarea::make('body')
                    ->label('Isi')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('audience_filter')
                    ->label('Filter Audiens')
                    ->required(),
                TextInput::make('channels')
                    ->label('Channel')
                    ->required(),
                DateTimePicker::make('scheduled_at')
                    ->label('Dijadwalkan'),
                DateTimePicker::make('sent_at')
                    ->label('Dikirim'),
                Toggle::make('is_emergency')
                    ->label('Darurat')
                    ->required(),
                Toggle::make('requires_approval')
                    ->label('Butuh Approval')
                    ->required(),
                TextInput::make('approved_by')
                    ->label('Disetujui Oleh')
                    ->numeric(),
                DateTimePicker::make('approved_at')
                    ->label('Tanggal Disetujui'),
                TextInput::make('recipient_count')
                    ->label('Jumlah Penerima')
                    ->required()
                    ->numeric()
                    ->default(0),
                Select::make('status')
                    ->label('Status')
                    ->options(AnnouncementStatus::class)
                    ->default('Draft')
                    ->required(),
            ]);
    }
}
