<?php

namespace App\Filament\Imports;

use App\Enums\Rbac\Role;
use App\Enums\Rbac\UserType;
use App\Models\Curriculum;
use App\Models\Student;
use App\Models\StudyProgram;
use App\Models\User;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Number;

class StudentImporter extends Importer
{
    protected static ?string $model = Student::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('full_name')
                ->label('Nama Lengkap')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            ImportColumn::make('email')
                ->label('Email')
                ->requiredMapping()
                ->rules(['required', 'email']),
            ImportColumn::make('password')
                ->label('Password')
                ->requiredMapping()
                ->rules(['required', 'string', 'min:6']),
            ImportColumn::make('nim')
                ->label('NIM')
                ->requiredMapping()
                ->rules(['required', 'string']),
            ImportColumn::make('study_program_code')
                ->label('Kode Program Studi')
                ->rules(['nullable', 'string']),
            ImportColumn::make('curriculum_code')
                ->label('Kode Kurikulum')
                ->rules(['nullable', 'string']),
            ImportColumn::make('enrollment_year')
                ->label('Tahun Masuk')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('status')
                ->label('Status')
                ->rules(['nullable', 'string']),
            ImportColumn::make('entry_path')
                ->label('Jalur Masuk')
                ->rules(['nullable', 'string']),
            ImportColumn::make('ukt_group')
                ->label('Golongan UKT')
                ->numeric()
                ->rules(['nullable', 'integer', 'between:1,8']),
            ImportColumn::make('gender')
                ->label('Jenis Kelamin')
                ->rules(['nullable', 'in:L,P']),
            ImportColumn::make('birth_place')
                ->label('Tempat Lahir')
                ->rules(['nullable', 'string']),
            ImportColumn::make('birth_date')
                ->label('Tanggal Lahir')
                ->rules(['nullable', 'date']),
            ImportColumn::make('phone')
                ->label('No. HP')
                ->rules(['nullable', 'string']),
            ImportColumn::make('parent_name')
                ->label('Nama Orang Tua')
                ->rules(['nullable', 'string']),
            ImportColumn::make('parent_phone')
                ->label('No. HP Orang Tua')
                ->rules(['nullable', 'string']),
            ImportColumn::make('religion')
                ->label('Agama')
                ->rules(['nullable', 'string']),
            ImportColumn::make('nationality')
                ->label('Kewarganegaraan')
                ->rules(['nullable', 'string']),
        ];
    }

    public function resolveRecord(): ?Student
    {
        $user = User::firstOrCreate(
            ['email' => $this->data['email']],
            [
                'name' => $this->data['full_name'],
                'full_name' => $this->data['full_name'],
                'username' => $this->data['nim'],
                'password' => Hash::make($this->data['password']),
                'user_type' => UserType::Student,
                'external_id' => $this->data['nim'] ?? null,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $user->assignRole(Role::Mahasiswa->value);

        if (! empty($this->data['study_program_code'])) {
            $this->data['study_program_id'] = StudyProgram::where('code', $this->data['study_program_code'])->value('id');
        }
        if (! empty($this->data['curriculum_code'])) {
            $this->data['curriculum_id'] = Curriculum::where('code', $this->data['curriculum_code'])->value('id');
        }

        if (empty($this->data['status'])) {
            $this->data['status'] = 'Aktif';
        }

        unset(
            $this->data['full_name'],
            $this->data['email'],
            $this->data['password'],
            $this->data['study_program_code'],
            $this->data['curriculum_code'],
        );

        $record = Student::firstOrNew(['user_id' => $user->id]);
        $record->user_id = $user->id;

        return $record;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Import Mahasiswa selesai. '.Number::format($import->successful_rows).' baris berhasil diimport.';

        if ($failed = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failed).' baris gagal.';
        }

        return $body;
    }
}
