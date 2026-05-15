<?php

namespace App\Filament\Admin\Resources\NotificationTemplates\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class NotificationTemplateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('Kode')
                    ->required(),
                TextInput::make('name')
                    ->label('Nama')
                    ->required(),
                TextInput::make('event')
                    ->label('Event'),
                TextInput::make('channels')
                    ->label('Channel')
                    ->required(),
                TextInput::make('subject')
                    ->label('Subjek'),
                Textarea::make('body_email')
                    ->label('Body Email')
                    ->columnSpanFull(),
                Textarea::make('body_wa')
                    ->label('Body WA')
                    ->columnSpanFull(),
                Textarea::make('body_push')
                    ->label('Body Push')
                    ->columnSpanFull(),
                Textarea::make('body_inapp')
                    ->label('Body In-App')
                    ->columnSpanFull(),
                TextInput::make('variables')
                    ->label('Variabel'),
                Toggle::make('is_mandatory')
                    ->label('Wajib')
                    ->required(),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->required(),
            ]);
    }
}
