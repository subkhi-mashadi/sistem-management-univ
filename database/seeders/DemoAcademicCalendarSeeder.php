<?php

namespace Database\Seeders;

use App\Enums\Academic\SemesterTerm;
use App\Models\AcademicCalendar;
use App\Models\Semester;
use Illuminate\Database\Seeder;

/**
 * Demo: 1 Kalender Akademik + 2 Semester (Ganjil aktif, Genap inactive).
 *
 * Cara pakai: php artisan db:seed --class=DemoAcademicCalendarSeeder
 */
class DemoAcademicCalendarSeeder extends Seeder
{

    public function run(): void
    {
        $year = (int) now()->format('Y');
        $nextYear = $year + 1;

        $calendar = AcademicCalendar::firstOrCreate(
            ['academic_year' => "{$year}/{$nextYear}"],
            [
                'name' => "Tahun Akademik {$year}/{$nextYear}",
                'start_date' => "{$year}-08-01",
                'end_date' => "{$nextYear}-07-31",
                'is_active' => true,
            ],
        );

        Semester::query()->update(['is_active' => false]);

        Semester::firstOrCreate(
            ['code' => "{$year}1"],
            [
                'academic_calendar_id' => $calendar->id,
                'name' => "Ganjil {$year}/{$nextYear}",
                'term' => SemesterTerm::Ganjil->value,
                'start_date' => "{$year}-08-01",
                'end_date' => "{$nextYear}-01-31",
                'krs_start' => "{$year}-08-01",
                'krs_end' => "{$year}-08-21",
                'lecture_start' => "{$year}-09-01",
                'lecture_end' => "{$year}-12-19",
                'uts_start' => "{$year}-10-20",
                'uts_end' => "{$year}-10-31",
                'uas_start' => "{$year}-12-22",
                'uas_end' => "{$nextYear}-01-09",
                'grade_input_deadline' => "{$nextYear}-01-20",
                'is_active' => true,
            ],
        );

        Semester::firstOrCreate(
            ['code' => "{$year}2"],
            [
                'academic_calendar_id' => $calendar->id,
                'name' => "Genap {$year}/{$nextYear}",
                'term' => SemesterTerm::Genap->value,
                'start_date' => "{$nextYear}-02-01",
                'end_date' => "{$nextYear}-07-31",
                'krs_start' => "{$nextYear}-02-01",
                'krs_end' => "{$nextYear}-02-21",
                'lecture_start' => "{$nextYear}-03-01",
                'lecture_end' => "{$nextYear}-06-19",
                'uts_start' => "{$nextYear}-04-20",
                'uts_end' => "{$nextYear}-04-30",
                'uas_start' => "{$nextYear}-06-22",
                'uas_end' => "{$nextYear}-07-09",
                'grade_input_deadline' => "{$nextYear}-07-20",
                'is_active' => false,
            ],
        );

        $active = Semester::where('is_active', true)->value('name');
        $this->command->info("✓ Kalender + 2 Semester dibuat (aktif: {$active})");
    }
}
