<?php

namespace App\Filament\Admin\Resources\Enrollments\Schemas;

use App\Enums\Krs\EnrollmentStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EnrollmentForm
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
                            ->relationship('student', 'id')
                            ->required(),
                        Select::make('semester_id')
                            ->label('Semester')
                            ->relationship('semester', 'name')
                            ->required(),
                        TextInput::make('max_sks')
                            ->label('SKS Maksimum')
                            ->required()
                            ->numeric(),
                        TextInput::make('total_sks_taken')
                            ->label('Total SKS Diambil')
                            ->required()
                            ->numeric()
                            ->default(0),
                        Select::make('status')
                            ->label('Status')
                            ->options(EnrollmentStatus::class)
                            ->default('Draft')
                            ->required(),
                        TextInput::make('approved_by')
                            ->label('Disetujui Oleh')
                            ->numeric(),
                        DateTimePicker::make('approved_at')
                            ->label('Tanggal Disetujui'),
                        Textarea::make('notes')
                            ->label('Catatan')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
