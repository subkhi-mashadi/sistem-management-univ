<?php

namespace App\Filament\Admin\Resources\LecturerWorkloads\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LecturerWorkloadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama')
                    ->columnSpanFull()
                    ->columns(2)->components([
                        Select::make('lecturer_id')
                            ->label('Dosen')
                            ->relationship('lecturer', 'nidn')->searchable()->preload()
                            ->required(),
                        Select::make('semester_id')
                            ->label('Semester')
                            ->relationship('semester', 'name')
                            ->required(),
                        TextInput::make('teaching_sks')
                            ->label('SKS Mengajar')
                            ->required()
                            ->numeric()
                            ->default(0.0),
                        TextInput::make('advisory_sks')
                            ->label('SKS Pembimbing')
                            ->required()
                            ->numeric()
                            ->default(0.0),
                        TextInput::make('research_sks')
                            ->label('SKS Penelitian')
                            ->required()
                            ->numeric()
                            ->default(0.0),
                        TextInput::make('service_sks')
                            ->label('SKS Pengabdian')
                            ->required()
                            ->numeric()
                            ->default(0.0),
                        TextInput::make('additional_sks')
                            ->label('SKS Tambahan')
                            ->required()
                            ->numeric()
                            ->default(0.0),
                        TextInput::make('total_sks')
                            ->label('Total SKS')
                            ->required()
                            ->numeric()
                            ->default(0.0),
                        Toggle::make('is_locked')
                            ->label('Terkunci')
                            ->required(),
                    ]),
            ]);
    }
}
