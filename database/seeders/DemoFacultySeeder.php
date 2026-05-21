<?php

namespace Database\Seeders;

use App\Enums\Academic\Accreditation;
use App\Enums\Academic\DegreeLevel;
use App\Models\Faculty;
use App\Models\StudyProgram;
use Illuminate\Database\Seeder;

/**
 * Demo: 3 Fakultas + 6 Program Studi standar kampus Indonesia.
 *
 * Cara pakai: php artisan db:seed --class=DemoFacultySeeder
 */
class DemoFacultySeeder extends Seeder
{

    public function run(): void
    {
        $faculties = [
            ['code' => 'FT',   'name' => 'Fakultas Teknik'],
            ['code' => 'FEB',  'name' => 'Fakultas Ekonomi & Bisnis'],
            ['code' => 'FISIP','name' => 'Fakultas Ilmu Sosial & Politik'],
        ];

        foreach ($faculties as $row) {
            Faculty::firstOrCreate(
                ['code' => $row['code']],
                ['name' => $row['name'], 'is_active' => true],
            );
        }

        $programs = [
            ['fak' => 'FT',    'code' => 'IF',  'name' => 'Informatika',        'jenjang' => DegreeLevel::S1],
            ['fak' => 'FT',    'code' => 'SI',  'name' => 'Sistem Informasi',   'jenjang' => DegreeLevel::S1],
            ['fak' => 'FT',    'code' => 'TE',  'name' => 'Teknik Elektro',     'jenjang' => DegreeLevel::S1],
            ['fak' => 'FEB',   'code' => 'MNJ', 'name' => 'Manajemen',          'jenjang' => DegreeLevel::S1],
            ['fak' => 'FEB',   'code' => 'AKT', 'name' => 'Akuntansi',          'jenjang' => DegreeLevel::S1],
            ['fak' => 'FISIP', 'code' => 'KOM', 'name' => 'Ilmu Komunikasi',    'jenjang' => DegreeLevel::S1],
        ];

        foreach ($programs as $row) {
            $faculty = Faculty::where('code', $row['fak'])->firstOrFail();
            StudyProgram::firstOrCreate(
                ['code' => $row['code']],
                [
                    'faculty_id' => $faculty->id,
                    'name' => $row['name'],
                    'degree_level' => $row['jenjang']->value,
                    'accreditation' => Accreditation::Unggul->value,
                    'accreditation_valid_until' => now()->addYears(4)->toDateString(),
                    'is_active' => true,
                ],
            );
        }

        $this->command->info('✓ '.Faculty::count().' Fakultas, '.StudyProgram::count().' Program Studi dibuat');
    }
}
