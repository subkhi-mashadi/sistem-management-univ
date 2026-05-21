<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Orchestrator: jalankan semua DemoXxx seeder dalam urutan dependency.
 *
 * Cara pakai: php artisan db:seed --class=DemoSeeder
 *
 * Aman dijalankan berulang (semua pakai firstOrCreate).
 * TIDAK akan menghapus data existing.
 */
class DemoSeeder extends Seeder
{

    public function run(): void
    {
        $this->call([
            DemoUktGroupSeeder::class,           // Golongan UKT (independent)
            DemoFacultySeeder::class,            // Fakultas + Prodi (independent)
            DemoClassroomSeeder::class,          // Ruangan (independent)
            DemoAcademicCalendarSeeder::class,   // Kalender + Semester (independent)
            DemoLecturerSeeder::class,           // Dosen (butuh Prodi)
            DemoCurriculumSeeder::class,         // Kurikulum + MK + Prasyarat + Skema Nilai (butuh Prodi)
            DemoStudentSeeder::class,            // Mahasiswa (butuh Prodi, Kurikulum, Dosen, UKT)
            DemoCourseOfferingSeeder::class,     // Penawaran MK + Jadwal (butuh MK, Semester, Dosen, Ruangan)
        ]);

        $this->command->info('');
        $this->command->info('✓ Semua data demo berhasil di-seed');
    }
}
