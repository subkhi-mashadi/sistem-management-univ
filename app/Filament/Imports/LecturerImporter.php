<?php

namespace App\Filament\Imports;

use App\Enums\Rbac\Role;
use App\Enums\Rbac\UserType;
use App\Models\Lecturer;
use App\Models\StudyProgram;
use App\Models\User;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Number;

class LecturerImporter extends Importer
{
    protected static ?string $model = Lecturer::class;

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
            ImportColumn::make('nidn')
                ->label('NIDN')
                ->rules(['nullable', 'string']),
            ImportColumn::make('nidk')
                ->label('NIDK')
                ->rules(['nullable', 'string']),
            ImportColumn::make('nip')
                ->label('NIP')
                ->rules(['nullable', 'string']),
            ImportColumn::make('study_program_code')
                ->label('Kode Program Studi')
                ->rules(['nullable', 'string']),
            ImportColumn::make('functional_position')
                ->label('Jabatan Fungsional')
                ->rules(['nullable', 'string']),
            ImportColumn::make('structural_position')
                ->label('Jabatan Struktural')
                ->rules(['nullable', 'string']),
            ImportColumn::make('education_level')
                ->label('Jenjang Pendidikan')
                ->rules(['nullable', 'in:S2,S3']),
            ImportColumn::make('employment_status')
                ->label('Status Kepegawaian')
                ->rules(['nullable', 'in:PNS,Tetap Yayasan,Kontrak,Honorer,Tamu']),
            ImportColumn::make('is_active')
                ->label('Aktif')
                ->boolean()
                ->rules(['nullable', 'boolean']),
        ];
    }

    public function resolveRecord(): ?Lecturer
    {
        $user = User::firstOrCreate(
            ['email' => $this->data['email']],
            [
                'name' => $this->data['full_name'],
                'full_name' => $this->data['full_name'],
                'username' => $this->data['email'],
                'password' => Hash::make($this->data['password']),
                'user_type' => UserType::Lecturer,
                'external_id' => $this->data['nidn'] ?? $this->data['nip'] ?? null,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $user->assignRole(Role::Dosen->value);

        if (! empty($this->data['study_program_code'])) {
            $this->data['study_program_id'] = StudyProgram::where('code', $this->data['study_program_code'])->value('id');
        }

        unset(
            $this->data['full_name'],
            $this->data['email'],
            $this->data['password'],
            $this->data['study_program_code'],
        );

        $record = Lecturer::firstOrNew(['user_id' => $user->id]);
        $record->user_id = $user->id;

        return $record;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Import Dosen selesai. '.Number::format($import->successful_rows).' baris berhasil diimport.';

        if ($failed = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failed).' baris gagal.';
        }

        return $body;
    }
}
