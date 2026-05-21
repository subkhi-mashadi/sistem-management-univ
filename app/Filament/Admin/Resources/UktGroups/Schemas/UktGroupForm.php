<?php

namespace App\Filament\Admin\Resources\UktGroups\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UktGroupForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Golongan UKT')
                    ->columnSpanFull()
                    ->columns(2)
                    ->components([
                        TextInput::make('code')
                            ->label('Kode Golongan')
                            ->placeholder('Auto-generate')
                            ->disabled()
                            ->dehydrated(false)
                            ->helperText('Otomatis di-generate (angka berurutan).'),
                        TextInput::make('name')
                            ->label('Nama Golongan')
                            ->required()
                            ->placeholder('Golongan I'),
                        TextInput::make('min_income')
                            ->label('Penghasilan Min. Ortu (Rp)')
                            ->numeric()
                            ->minValue(0)
                            ->prefix('Rp')
                            ->helperText('Opsional, batas bawah penghasilan orang tua.'),
                        TextInput::make('max_income')
                            ->label('Penghasilan Maks. Ortu (Rp)')
                            ->numeric()
                            ->minValue(0)
                            ->prefix('Rp')
                            ->helperText('Opsional, batas atas penghasilan orang tua.'),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->columnSpanFull()
                            ->rows(2),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->inline(false),
                    ]),
            ]);
    }
}
