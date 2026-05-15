<?php

namespace App\Filament\Admin\Resources\Students\Schemas;

use App\Enums\Academic\EntryPath;
use App\Enums\Academic\StudentStatus;
use App\Enums\Common\Gender;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class StudentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Pengguna')
                    ->relationship('user', 'name')
                    ->required(),
                TextInput::make('nim')
                    ->label('NIM')
                    ->required(),
                Select::make('study_program_id')
                    ->label('Program Studi')
                    ->relationship('studyProgram', 'name')
                    ->required(),
                Select::make('curriculum_id')
                    ->label('Kurikulum')
                    ->relationship('curriculum', 'id')
                    ->required(),
                TextInput::make('enrollment_year')
                    ->label('Tahun Masuk')
                    ->required(),
                TextInput::make('academic_advisor_id')
                    ->label('Dosen Wali')
                    ->numeric(),
                Select::make('status')
                    ->label('Status')
                    ->options(StudentStatus::class)
                    ->default('Aktif')
                    ->required(),
                Select::make('entry_path')
                    ->label('Jalur Masuk')
                    ->options(EntryPath::class),
                TextInput::make('ukt_group')
                    ->label('Golongan UKT')
                    ->numeric(),
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
                TextInput::make('parent_name')
                    ->label('Nama Wali'),
                TextInput::make('parent_phone')
                    ->label('Telepon Wali')
                    ->tel(),
                TextInput::make('religion')
                    ->label('Agama'),
                TextInput::make('nationality')
                    ->label('Kewarganegaraan')
                    ->default('Indonesia'),
            ]);
    }
}
