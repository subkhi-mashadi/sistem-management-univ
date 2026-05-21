<?php

namespace App\Filament\Admin\Resources\Transcripts\Schemas;

use App\Enums\Krs\AcademicStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TranscriptForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama')
                    ->columnSpanFull()
                    ->columns(2)->components([
                        Select::make('student_id')
                            ->label('Mahasiswa')
                            ->relationship('student', 'nim')->searchable()->preload()
                            ->required(),
                        Select::make('semester_id')
                            ->label('Semester')
                            ->relationship('semester', 'name')
                            ->required(),
                        TextInput::make('sks_attempted')
                            ->label('SKS Ditempuh')
                            ->required()
                            ->numeric()
                            ->default(0),
                        TextInput::make('sks_acquired')
                            ->label('SKS Lulus')
                            ->required()
                            ->numeric()
                            ->default(0),
                        TextInput::make('semester_gpa')
                            ->label('IPS')
                            ->required()
                            ->numeric()
                            ->default(0.0),
                        TextInput::make('cumulative_gpa')
                            ->label('IPK')
                            ->required()
                            ->numeric()
                            ->default(0.0),
                        TextInput::make('sks_cumulative')
                            ->label('SKS Kumulatif')
                            ->required()
                            ->numeric()
                            ->default(0),
                        Select::make('academic_status')
                            ->label('Status Akademik')
                            ->options(AcademicStatus::class)
                            ->default('Normal')
                            ->required(),
                    ]),
            ]);
    }
}
