<?php

namespace App\Filament\Admin\Resources\Attendances\Schemas;

use App\Enums\Scheduling\AttendanceStatus;
use App\Enums\Scheduling\CheckMethod;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('class_session_id')
                    ->label('Sesi Kelas')
                    ->relationship('classSession', 'id')
                    ->required(),
                Select::make('student_id')
                    ->label('Mahasiswa')
                    ->relationship('student', 'id')
                    ->required(),
                Select::make('status')
                    ->label('Status')
                    ->options(AttendanceStatus::class)
                    ->default('Hadir')
                    ->required(),
                Select::make('check_method')
                    ->label('Metode Absensi')
                    ->options(CheckMethod::class),
                DateTimePicker::make('check_in_at')
                    ->label('Waktu Absen'),
                TextInput::make('notes')
                    ->label('Catatan'),
            ]);
    }
}
