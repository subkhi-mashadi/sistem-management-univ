<?php

namespace Database\Seeders;

use App\Enums\Scheduling\DayOfWeek;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\CourseOffering;
use App\Models\Lecturer;
use App\Models\Schedule;
use App\Models\Semester;
use Illuminate\Database\Seeder;

/**
 * Demo: Penawaran MK + Jadwal untuk semester aktif.
 * Hanya MK semester 1 & 2 (10-13 MK) yang dibuka, sebagai contoh.
 *
 * Cara pakai: php artisan db:seed --class=DemoCourseOfferingSeeder
 * Prasyarat: DemoAcademicCalendarSeeder, DemoLecturerSeeder, DemoClassroomSeeder, DemoCurriculumSeeder.
 */
class DemoCourseOfferingSeeder extends Seeder
{

    public function run(): void
    {
        $semester = Semester::where('is_active', true)->first();
        if (! $semester) {
            $this->command->error('Belum ada Semester aktif. Jalankan DemoAcademicCalendarSeeder dulu.');

            return;
        }

        $rooms = Classroom::where('is_active', true)->get();
        $lecturers = Lecturer::where('is_active', true)->get();
        if ($rooms->isEmpty() || $lecturers->isEmpty()) {
            $this->command->error('Butuh Ruangan & Dosen. Jalankan DemoClassroomSeeder & DemoLecturerSeeder dulu.');

            return;
        }

        $courses = Course::whereIn('semester', [1, 2])
            ->where('is_active', true)
            ->get();
        if ($courses->isEmpty()) {
            $this->command->error('Belum ada MK. Jalankan DemoCurriculumSeeder dulu.');

            return;
        }

        $days = [DayOfWeek::Senin, DayOfWeek::Selasa, DayOfWeek::Rabu, DayOfWeek::Kamis, DayOfWeek::Jumat];
        $slots = ['08:00', '10:30', '13:00', '15:30'];
        $duration = '02:30';

        $idx = 0;
        foreach ($courses as $course) {
            $lecturer = $lecturers->random();
            $room = $rooms->random();
            $day = $days[$idx % count($days)];
            $start = $slots[(int) ($idx / count($days)) % count($slots)];
            $end = $this->addDuration($start, $duration);
            $idx++;

            $offering = CourseOffering::firstOrCreate(
                [
                    'course_id' => $course->id,
                    'semester_id' => $semester->id,
                    'class_code' => 'A',
                ],
                [
                    'lecturer_ids' => [$lecturer->id],
                    'quota' => 40,
                    'enrolled_count' => 0,
                    'is_open' => true,
                ],
            );

            Schedule::firstOrCreate(
                [
                    'course_offering_id' => $offering->id,
                    'day_of_week' => $day->value,
                    'start_time' => $start,
                ],
                [
                    'classroom_id' => $room->id,
                    'end_time' => $end,
                    'meeting_count' => 14,
                ],
            );
        }

        $offeringsCount = CourseOffering::where('semester_id', $semester->id)->count();
        $this->command->info("✓ {$offeringsCount} Penawaran MK + Jadwal dibuat untuk Semester {$semester->name}");
    }

    private function addDuration(string $start, string $duration): string
    {
        [$sh, $sm] = array_map('intval', explode(':', $start));
        [$dh, $dm] = array_map('intval', explode(':', $duration));
        $total = $sh * 60 + $sm + $dh * 60 + $dm;

        return sprintf('%02d:%02d', intdiv($total, 60), $total % 60);
    }
}
