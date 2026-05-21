<?php

namespace Database\Seeders;

use App\Enums\Academic\EntryPath;
use App\Enums\Academic\StudentStatus;
use App\Enums\Common\Gender;
use App\Enums\Common\Religion;
use App\Enums\Rbac\Role as RoleEnum;
use App\Enums\Rbac\UserType;
use App\Models\Curriculum;
use App\Models\Lecturer;
use App\Models\Student;
use App\Models\StudyProgram;
use App\Models\UktGroup;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Demo: 30 Mahasiswa dengan distribusi otomatis ke Prodi yang ada.
 * Tiap mahasiswa di-assign Prodi → Kurikulum aktif prodi tsb → Dosen Wali dari prodi tsb → Golongan UKT random.
 *
 * Cara pakai: php artisan db:seed --class=DemoStudentSeeder
 * Prasyarat: DemoFacultySeeder, DemoCurriculumSeeder, DemoLecturerSeeder, DemoUktGroupSeeder sudah jalan.
 */
class DemoStudentSeeder extends Seeder
{

    public function run(): void
    {
        $programs = StudyProgram::with(['curriculums' => fn ($q) => $q->where('is_active', true)])
            ->get();

        if ($programs->isEmpty()) {
            $this->command->error('Belum ada Prodi. Jalankan DemoFacultySeeder dulu.');

            return;
        }

        $uktGroups = UktGroup::where('is_active', true)->pluck('code')->all();
        if (empty($uktGroups)) {
            $this->command->warn('Belum ada Golongan UKT. Lanjut dengan UKT kosong.');
        }

        $names = [
            ['Andi Wijaya',          Gender::L, Religion::Islam,            'Jakarta'],
            ['Budi Santoso',         Gender::L, Religion::Islam,            'Bandung'],
            ['Citra Lestari',        Gender::P, Religion::KristenProtestan, 'Surabaya'],
            ['Dewi Pratiwi',         Gender::P, Religion::Islam,            'Yogyakarta'],
            ['Eka Saputra',          Gender::L, Religion::Hindu,            'Denpasar'],
            ['Fajar Nugroho',        Gender::L, Religion::Islam,            'Semarang'],
            ['Gita Anggraini',       Gender::P, Religion::Katolik,          'Medan'],
            ['Hadi Setiawan',        Gender::L, Religion::Islam,            'Makassar'],
            ['Indah Permata Sari',   Gender::P, Religion::Islam,            'Padang'],
            ['Joko Saputra',         Gender::L, Religion::Islam,            'Solo'],
            ['Kartika Rahayu',       Gender::P, Religion::Buddha,           'Pontianak'],
            ['Lukman Hakim',         Gender::L, Religion::Islam,            'Malang'],
            ['Maya Sari',            Gender::P, Religion::Islam,            'Palembang'],
            ['Nanda Pratama',        Gender::L, Religion::KristenProtestan, 'Manado'],
            ['Oki Setiadi',          Gender::L, Religion::Islam,            'Banjarmasin'],
            ['Putri Maharani',       Gender::P, Religion::Islam,            'Pekanbaru'],
            ['Qori Hidayat',         Gender::L, Religion::Islam,            'Lampung'],
            ['Rahma Wati',           Gender::P, Religion::Islam,            'Tangerang'],
            ['Sigit Prasetyo',       Gender::L, Religion::Katolik,          'Bekasi'],
            ['Tania Marlina',        Gender::P, Religion::Islam,            'Depok'],
            ['Umar Hidayat',         Gender::L, Religion::Islam,            'Bogor'],
            ['Vina Yulianti',        Gender::P, Religion::Islam,            'Jakarta'],
            ['Wahyu Saputra',        Gender::L, Religion::Hindu,            'Singaraja'],
            ['Xena Putri',           Gender::P, Religion::KristenProtestan, 'Ambon'],
            ['Yusuf Hakim',          Gender::L, Religion::Islam,            'Banda Aceh'],
            ['Zahra Aulia',          Gender::P, Religion::Islam,            'Cirebon'],
            ['Abdul Rahman',         Gender::L, Religion::Islam,            'Jambi'],
            ['Bunga Citra',          Gender::P, Religion::Buddha,           'Batam'],
            ['Candra Wijaya',        Gender::L, Religion::Konghucu,         'Singkawang'],
            ['Dian Lestari',         Gender::P, Religion::Islam,            'Mataram'],
        ];

        $entryPaths = [EntryPath::SNBP, EntryPath::SNBT, EntryPath::Mandiri];
        $year = (int) now()->format('Y');

        foreach ($names as $i => [$name, $gender, $religion, $birthPlace]) {
            $prodi = $programs[$i % $programs->count()];
            $curriculum = $prodi->curriculums->first()
                ?? Curriculum::where('study_program_id', $prodi->id)->where('is_active', true)->first();

            if (! $curriculum) {
                $this->command->warn("Skip {$name} — Prodi {$prodi->code} belum punya Kurikulum aktif.");
                continue;
            }

            $advisor = Lecturer::where('study_program_id', $prodi->id)
                ->where('is_active', true)
                ->inRandomOrder()
                ->first();

            $nim = sprintf('%d%05d', $year, $i + 1);
            $email = "{$nim}@student.univercity.test";

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'username' => $nim,
                    'name' => $name,
                    'full_name' => $name,
                    'user_type' => UserType::Student,
                    'password' => Hash::make($nim),
                    'is_active' => true,
                    'email_verified_at' => now(),
                ],
            );

            if (! $user->hasRole(RoleEnum::Mahasiswa->value)) {
                $user->assignRole(RoleEnum::Mahasiswa->value);
            }

            Student::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'nim' => $nim,
                    'study_program_id' => $prodi->id,
                    'curriculum_id' => $curriculum->id,
                    'enrollment_year' => $year,
                    'academic_advisor_id' => $advisor?->id,
                    'status' => StudentStatus::Aktif->value,
                    'entry_path' => $entryPaths[$i % count($entryPaths)]->value,
                    'ukt_group' => $uktGroups ? $uktGroups[$i % count($uktGroups)] : null,
                    'gender' => $gender->value,
                    'birth_place' => $birthPlace,
                    'birth_date' => now()->subYears(18 + ($i % 4))->subDays(rand(0, 365))->toDateString(),
                    'phone' => '08'.rand(1000000000, 9999999999),
                    'parent_name' => 'Wali '.$name,
                    'parent_phone' => '08'.rand(1000000000, 9999999999),
                    'religion' => $religion->value,
                    'nationality' => 'Indonesia',
                ],
            );
        }

        $this->command->info('✓ '.Student::count().' Mahasiswa dibuat (terdistribusi ke '.$programs->count().' Prodi).');
        $this->command->warn('Login: <NIM>@student.univercity.test, password = NIM');
    }
}
