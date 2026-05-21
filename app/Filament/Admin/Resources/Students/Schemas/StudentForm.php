<?php

namespace App\Filament\Admin\Resources\Students\Schemas;

use App\Enums\Academic\EntryPath;
use App\Enums\Academic\StudentStatus;
use App\Enums\Common\Gender;
use App\Enums\Common\Religion;
use App\Models\Curriculum;
use App\Models\Lecturer;
use App\Models\UktGroup;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class StudentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Akun Login Mahasiswa')
                    ->description('User account otomatis dibuat dengan role Mahasiswa.')
                    ->columns(2)
                    ->columnSpanFull()
                    ->components([
                        TextInput::make('full_name')
                            ->label('Nama Lengkap')
                            ->required()
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
                            ->hiddenOn('edit')
                            ->helperText('Default: NIM mahasiswa atau password manual.'),
                    ]),
                Section::make('Data Akademik')
                    ->columnSpanFull()
                    ->columns(2)
                    ->components([
                        TextInput::make('full_name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->hiddenOn('edit')
                            ->columnSpanFull()
                            ->helperText('Akun login otomatis dibuat. Username = NIM, password default = NIM.'),
                        TextInput::make('nim')
                            ->label('NIM')
                            ->required()
                            ->unique(ignoreRecord: true),
                        TextInput::make('enrollment_year')
                            ->label('Tahun Masuk')
                            ->numeric()
                            ->minValue(2000)
                            ->maxValue(now()->year + 1)
                            ->default(now()->year)
                            ->required(),
                        Select::make('study_program_id')
                            ->label('Program Studi')
                            ->relationship('studyProgram', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Set $set) {
                                $set('curriculum_id', null);
                                $set('academic_advisor_id', null);
                            }),
                        Select::make('curriculum_id')
                            ->label('Kurikulum')
                            ->options(function (Get $get) {
                                $prodiId = $get('study_program_id');
                                if (! $prodiId) {
                                    return [];
                                }

                                return Curriculum::query()
                                    ->where('study_program_id', $prodiId)
                                    ->where('is_active', true)
                                    ->orderByDesc('effective_year')
                                    ->pluck('name', 'id')
                                    ->all();
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->disabled(fn (Get $get) => ! $get('study_program_id')),
                        Select::make('academic_advisor_id')
                            ->label('Dosen Wali')
                            ->options(function (Get $get) {
                                $prodiId = $get('study_program_id');
                                if (! $prodiId) {
                                    return [];
                                }

                                return Lecturer::query()
                                    ->where('study_program_id', $prodiId)
                                    ->where('is_active', true)
                                    ->with('user:id,full_name,name')
                                    ->get()
                                    ->mapWithKeys(fn (Lecturer $l) => [
                                        $l->id => ($l->user?->full_name ?? $l->user?->name)
                                    ])
                                    ->all();
                            })
                            ->searchable()
                            ->preload()
                            ->disabled(fn (Get $get) => ! $get('study_program_id')),
                        Select::make('status')
                            ->label('Status')
                            ->options(StudentStatus::options())
                            ->default(StudentStatus::Aktif->value)
                            ->native(false)
                            ->required(),
                        Select::make('entry_path')
                            ->label('Jalur Masuk')
                            ->options(EntryPath::options())
                            ->native(false),
                        Select::make('ukt_group')
                            ->label('Golongan UKT')
                            ->options(fn () => UktGroup::query()
                                ->where('is_active', true)
                                ->orderBy('code')
                                ->get()
                                ->mapWithKeys(fn (UktGroup $g) => [$g->code => "{$g->code} — {$g->name}"])
                                ->all())
                            ->searchable()
                            ->native(false)
                            ->helperText('Daftar golongan dikelola di menu Keuangan › Golongan UKT.'),
                    ]),

                Section::make('Biodata')
                    ->columnSpanFull()
                    ->columns(2)->components([
                        Select::make('gender')
                            ->label('Jenis Kelamin')
                            ->options(Gender::options())
                            ->native(false),
                        TextInput::make('birth_place')
                            ->label('Tempat Lahir'),
                        DatePicker::make('birth_date')
                            ->label('Tanggal Lahir')
                            ->native(false),
                        TextInput::make('phone')
                            ->label('Telepon')
                            ->tel(),
                        Select::make('religion')
                            ->label('Agama')
                            ->options(Religion::options())
                            ->native(false)
                            ->searchable(),
                        TextInput::make('nationality')
                            ->label('Kewarganegaraan')
                            ->default('Indonesia'),
                        Textarea::make('address')
                            ->label('Alamat')
                            ->columnSpanFull()
                            ->rows(2),
                    ]),

                Section::make('Wali')
                    ->columnSpanFull()
                    ->columns(2)->components([
                        TextInput::make('parent_name')
                            ->label('Nama Wali'),
                        TextInput::make('parent_phone')
                            ->label('Telepon Wali')
                            ->tel(),
                    ]),
            ]);
    }
}
