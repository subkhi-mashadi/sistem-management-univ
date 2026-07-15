<?php

namespace App\Support\EOffice;

use App\Models\Semester;
use App\Models\Student;
use App\Models\Transcript;

/**
 * Katalog field data mahasiswa yang bisa dipilih di Template Surat.
 * Nilainya diambil otomatis dari sistem saat mahasiswa mengajukan surat —
 * admin cukup centang, mahasiswa tidak perlu mengetik ulang datanya sendiri.
 */
class LetterSystemFields
{
    /** @return array<string, string> key => label, buat CheckboxList di form */
    public static function options(): array
    {
        return [
            'nama' => 'Nama Mahasiswa',
            'nim' => 'NIM',
            'prodi' => 'Program Studi',
            'fakultas' => 'Fakultas',
            'angkatan' => 'Angkatan',
            'semester_aktif' => 'Semester Aktif',
            'dosen_wali' => 'Dosen Wali',
            'ipk' => 'IPK',
            'status_akademik' => 'Status Akademik',
        ];
    }

    public static function label(string $key): string
    {
        return static::options()[$key] ?? ucfirst(str_replace('_', ' ', $key));
    }

    /**
     * @param  array<int, string>  $keys
     * @return array<string, string>
     */
    public static function resolve(array $keys, Student $student): array
    {
        $student->loadMissing(['user', 'studyProgram.faculty', 'advisor.user']);

        $lastTranscript = Transcript::where('student_id', $student->id)
            ->orderByDesc('semester_id')
            ->first();

        $available = [
            'nama' => fn () => $student->user?->name ?? '—',
            'nim' => fn () => $student->nim,
            'prodi' => fn () => $student->studyProgram?->name ?? '—',
            'fakultas' => fn () => $student->studyProgram?->faculty?->name ?? '—',
            'angkatan' => fn () => (string) $student->enrollment_year,
            'semester_aktif' => fn () => Semester::where('is_active', true)->value('name') ?? '—',
            'dosen_wali' => fn () => $student->advisor?->user?->name ?? '—',
            'ipk' => fn () => $lastTranscript?->cumulative_gpa ? number_format((float) $lastTranscript->cumulative_gpa, 2) : '—',
            'status_akademik' => fn () => $lastTranscript?->academic_status?->value ?? '—',
        ];

        $result = [];
        foreach ($keys as $key) {
            if (isset($available[$key])) {
                $result[$key] = $available[$key]();
            }
        }

        return $result;
    }
}
