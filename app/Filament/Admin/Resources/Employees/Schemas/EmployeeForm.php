<?php

namespace App\Filament\Admin\Resources\Employees\Schemas;

use App\Enums\Common\Gender;
use App\Enums\Hris\EmployeeStatus;
use App\Enums\Hris\EmployeeType;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Pengguna')
                    ->relationship('user', 'name')
                    ->required(),
                TextInput::make('nip')
                    ->label('NIP'),
                TextInput::make('nidn')
                    ->label('NIDN'),
                TextInput::make('nik')
                    ->label('NIK'),
                TextInput::make('full_name')
                    ->label('Nama Lengkap')
                    ->required(),
                Select::make('gender')
                    ->label('Jenis Kelamin')
                    ->options(Gender::class),
                TextInput::make('birth_place')
                    ->label('Tempat Lahir'),
                DatePicker::make('birth_date')
                    ->label('Tanggal Lahir'),
                Textarea::make('address')
                    ->label('Alamat')
                    ->columnSpanFull(),
                TextInput::make('phone')
                    ->label('Telepon')
                    ->tel(),
                Select::make('employee_type')
                    ->label('Tipe Pegawai')
                    ->options(EmployeeType::class)
                    ->required(),
                Toggle::make('is_lecturer')
                    ->label('Dosen')
                    ->required(),
                TextInput::make('structural_position')
                    ->label('Jabatan Struktural'),
                TextInput::make('functional_position')
                    ->label('Jabatan Fungsional'),
                TextInput::make('rank_grade')
                    ->label('Golongan'),
                TextInput::make('unit_id')
                    ->label('Unit')
                    ->numeric(),
                TextInput::make('base_salary')
                    ->label('Gaji Pokok')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('bank_name')
                    ->label('Nama Bank'),
                TextInput::make('bank_account')
                    ->label('Nomor Rekening'),
                TextInput::make('npwp')
                    ->label('NPWP'),
                TextInput::make('bpjs_kesehatan')
                    ->label('BPJS Kesehatan'),
                TextInput::make('bpjs_ketenagakerjaan')
                    ->label('BPJS Ketenagakerjaan'),
                DatePicker::make('start_date')
                    ->label('Tanggal Mulai'),
                DatePicker::make('end_date')
                    ->label('Tanggal Selesai'),
                Select::make('status')
                    ->label('Status')
                    ->options(EmployeeStatus::class)
                    ->default('Active')
                    ->required(),
            ]);
    }
}
