<?php

namespace App\Filament\Admin\Resources\Audiences\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AudienceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama')
                    ->columnSpanFull()
                    ->columns(2)->components([
                        TextInput::make('name')
                            ->label('Nama')
                            ->required(),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->columnSpanFull(),
                        TextInput::make('filter_rules')
                            ->label('Aturan Filter')
                            ->required(),
                        Toggle::make('is_dynamic')
                            ->label('Dinamis')
                            ->required(),
                        TextInput::make('last_count')
                            ->label('Jumlah Terakhir')
                            ->required()
                            ->numeric()
                            ->default(0),
                        DateTimePicker::make('last_resolved_at')
                            ->label('Diresolusi Terakhir'),
                    ]),
            ]);
    }
}
