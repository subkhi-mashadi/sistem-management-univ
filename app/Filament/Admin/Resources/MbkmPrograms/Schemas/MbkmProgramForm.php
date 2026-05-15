<?php

namespace App\Filament\Admin\Resources\MbkmPrograms\Schemas;

use App\Enums\Thesis\MbkmType;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MbkmProgramForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama')
                    ->columnSpanFull()
                    ->columns(2)->components([
                        TextInput::make('code')
                            ->label('Kode')
                            ->placeholder('Auto-generate')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('name')
                            ->label('Nama')
                            ->required(),
                        Select::make('type')
                            ->label('Tipe')
                            ->options(MbkmType::class)
                            ->required(),
                        TextInput::make('partner_name')
                            ->label('Nama Mitra'),
                        Textarea::make('partner_address')
                            ->label('Alamat Mitra')
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->columnSpanFull(),
                        TextInput::make('total_sks')
                            ->label('Total SKS')
                            ->required()
                            ->numeric(),
                        DatePicker::make('start_date')
                            ->label('Tanggal Mulai'),
                        DatePicker::make('end_date')
                            ->label('Tanggal Selesai'),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->required(),
                    ]),
            ]);
    }
}
