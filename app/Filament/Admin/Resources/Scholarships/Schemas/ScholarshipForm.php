<?php

namespace App\Filament\Admin\Resources\Scholarships\Schemas;

use App\Enums\Finance\CoverageType;
use App\Enums\Finance\ScholarshipType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ScholarshipForm
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
                            ->options(ScholarshipType::class)
                            ->required(),
                        TextInput::make('covered_components')
                            ->label('Komponen Ditanggung'),
                        Select::make('coverage_type')
                            ->label('Tipe Coverage')
                            ->options(CoverageType::class)
                            ->required(),
                        TextInput::make('coverage_value')
                            ->label('Nilai Coverage')
                            ->required()
                            ->numeric()
                            ->default(0.0),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->columnSpanFull(),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->required(),
                    ]),
            ]);
    }
}
