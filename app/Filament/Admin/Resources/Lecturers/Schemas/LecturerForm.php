<?php

namespace App\Filament\Admin\Resources\Lecturers\Schemas;

use App\Enums\Academic\EducationLevel;
use App\Enums\Academic\EmploymentStatus;
use App\Enums\Academic\FunctionalPosition;
use App\Enums\Academic\StructuralPosition;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LecturerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Akun Login Dosen')
                    ->description('User account otomatis dibuat dan diberi role Dosen.')
                    ->columns(2)
                    ->columnSpanFull()
                    ->components([
                        TextInput::make('full_name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255)
                            ->hiddenOn('edit'),
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique('users', 'email', ignoreRecord: true)
                            ->hiddenOn('edit'),
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->revealable()
                            ->minLength(8)
                            ->required()
                            ->dehydrated()
                            ->hiddenOn('edit'),
                        TextInput::make('phone')
                            ->label('Telepon')
                            ->tel()
                            ->hiddenOn('edit'),
                    ]),

                Section::make('Keahlian')
                    ->columnSpanFull()
                    ->components([
                        TagsInput::make('expertise_keywords')
                            ->label('Bidang Keahlian')
                            ->placeholder('Ketik lalu Enter')
                            ->helperText('Kata kunci bidang keahlian (untuk matching pembimbing skripsi).'),
                    ]),

                Section::make('Data Kepegawaian')
                    ->columnSpanFull()
                    ->columns(2)->components([
                        TextInput::make('nidn')
                            ->label('NIDN')
                            ->unique(ignoreRecord: true),
                        TextInput::make('nidk')
                            ->label('NIDK')
                            ->unique(ignoreRecord: true),
                        TextInput::make('nip')
                            ->label('NIP')
                            ->unique(ignoreRecord: true),
                        Select::make('study_program_id')
                            ->label('Program Studi')
                            ->relationship('studyProgram', 'name')
                            ->searchable()
                            ->preload(),
                        Select::make('functional_position')
                            ->label('Jabatan Fungsional')
                            ->options(FunctionalPosition::options())
                            ->native(false),
                        Select::make('structural_position')
                            ->label('Jabatan Struktural')
                            ->options(StructuralPosition::options())
                            ->native(false),
                        Select::make('education_level')
                            ->label('Pendidikan')
                            ->options(EducationLevel::options())
                            ->native(false),
                        Select::make('employment_status')
                            ->label('Status Kepegawaian')
                            ->options(EmploymentStatus::options())
                            ->native(false),
                        DatePicker::make('start_date')
                            ->label('Tanggal Mulai'),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->inline(false),
                    ]),

            ]);
    }
}
