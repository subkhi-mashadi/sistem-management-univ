<?php

namespace App\Filament\Admin\Resources\AttendanceRecords\Schemas;

use App\Enums\Hris\EmployeeAttendanceStatus;
use App\Enums\Hris\EmployeeCheckMethod;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AttendanceRecordForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('employee_id')
                    ->label('Pegawai')
                    ->relationship('employee', 'id')
                    ->required(),
                DatePicker::make('work_date')
                    ->label('Tanggal Kerja')
                    ->required(),
                DateTimePicker::make('check_in_at')
                    ->label('Waktu Absen'),
                DateTimePicker::make('check_out_at')
                    ->label('Jam Pulang'),
                Select::make('check_in_method')
                    ->label('Metode Masuk')
                    ->options(EmployeeCheckMethod::class),
                Select::make('check_out_method')
                    ->label('Metode Pulang')
                    ->options(EmployeeCheckMethod::class),
                TextInput::make('check_in_location')
                    ->label('Lokasi Masuk'),
                TextInput::make('check_out_location')
                    ->label('Lokasi Pulang'),
                TextInput::make('late_minutes')
                    ->label('Terlambat (Menit)')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('early_leave_minutes')
                    ->label('Pulang Cepat (Menit)')
                    ->required()
                    ->numeric()
                    ->default(0),
                Select::make('status')
                    ->label('Status')
                    ->options(EmployeeAttendanceStatus::class)
                    ->default('Hadir')
                    ->required(),
                TextInput::make('notes')
                    ->label('Catatan'),
            ]);
    }
}
