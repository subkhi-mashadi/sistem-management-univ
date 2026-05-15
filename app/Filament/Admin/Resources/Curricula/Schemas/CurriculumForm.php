<?php

namespace App\Filament\Admin\Resources\Curricula\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CurriculumForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Kurikulum')
                    ->columns(2)
                    ->columnSpanFull()
                    ->components([
                        TextInput::make('code')
                            ->label('Kode')
                            ->placeholder('Auto-generate')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('name')
                            ->label('Nama Kurikulum')
                            ->required()
                            ->maxLength(255),
                        Select::make('study_program_id')
                            ->label('Program Studi')
                            ->relationship('studyProgram', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('version')
                            ->label('Versi')
                            ->required()
                            ->placeholder('mis. 2023, 2024-Revisi'),
                        TextInput::make('effective_year')
                            ->label('Tahun Berlaku')
                            ->numeric()
                            ->minValue(2000)
                            ->maxValue(now()->year + 5)
                            ->default(now()->year)
                            ->required(),
                        TextInput::make('total_sks_required')
                            ->label('Total SKS Wajib')
                            ->numeric()
                            ->minValue(100)
                            ->maxValue(200)
                            ->default(144)
                            ->required()
                            ->suffix('SKS'),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->inline(false),
                        Toggle::make('is_archived')
                            ->label('Diarsipkan')
                            ->inline(false)
                            ->helperText('Kurikulum lama yang sudah tidak dipakai mahasiswa baru.'),
                    ]),
            ]);
    }
}
