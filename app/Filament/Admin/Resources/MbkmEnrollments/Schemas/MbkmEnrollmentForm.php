<?php

namespace App\Filament\Admin\Resources\MbkmEnrollments\Schemas;

use App\Enums\Thesis\MbkmEnrollmentStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MbkmEnrollmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('mbkm_program_id')
                    ->label('Program MBKM')
                    ->required()
                    ->numeric(),
                Select::make('student_id')
                    ->label('Mahasiswa')
                    ->relationship('student', 'id')
                    ->required(),
                Select::make('semester_id')
                    ->label('Semester')
                    ->relationship('semester', 'name')
                    ->required(),
                Select::make('status')
                    ->label('Status')
                    ->options(MbkmEnrollmentStatus::class)
                    ->default('Registered')
                    ->required(),
                TextInput::make('approved_by')
                    ->label('Disetujui Oleh')
                    ->numeric(),
                DateTimePicker::make('approved_at')
                    ->label('Tanggal Disetujui'),
                TextInput::make('final_score')
                    ->label('Nilai UAS')
                    ->numeric(),
                TextInput::make('report_url')
                    ->label('Laporan')
                    ->url(),
            ]);
    }
}
