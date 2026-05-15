<?php

namespace App\Filament\Admin\Resources\LetterArchives\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LetterArchiveForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama')
                    ->columnSpanFull()
                    ->columns(2)->components([
                        Select::make('letter_request_id')
                            ->label('Pengajuan Surat')
                            ->relationship('letterRequest', 'id'),
                        TextInput::make('letter_number')
                            ->label('Nomor Surat')
                            ->required(),
                        TextInput::make('category')
                            ->label('Kategori')
                            ->required(),
                        TextInput::make('subject')
                            ->label('Subjek')
                            ->required(),
                        TextInput::make('pdf_url')
                            ->label('PDF')
                            ->url()
                            ->required(),
                        Textarea::make('searchable_text')
                            ->label('Teks Cari')
                            ->columnSpanFull(),
                        TextInput::make('metadata')
                            ->label('Metadata'),
                        DateTimePicker::make('archived_at')
                            ->label('Diarsipkan Pada')
                            ->required(),
                    ]),
            ]);
    }
}
