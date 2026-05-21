<?php

namespace Database\Seeders;

use App\Enums\Academic\EducationLevel;
use App\Enums\Academic\EmploymentStatus;
use App\Enums\Academic\FunctionalPosition;
use App\Enums\Academic\StructuralPosition;
use App\Enums\Rbac\Role as RoleEnum;
use App\Enums\Rbac\UserType;
use App\Models\Lecturer;
use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Demo: 12 Dosen tersebar di semua Prodi.
 * Per Fakultas: 1 dosen jadi Dekan, per Prodi: 1 jadi Kaprodi.
 * Observer (LecturerObserver) otomatis sinkron ke Faculty.dean_id & StudyProgram.head_id.
 *
 * Cara pakai: php artisan db:seed --class=DemoLecturerSeeder
 * Prasyarat: DemoFacultySeeder sudah jalan.
 */
class DemoLecturerSeeder extends Seeder
{

    public function run(): void
    {
        if (StudyProgram::count() === 0) {
            $this->command->error('Belum ada Prodi. Jalankan DemoFacultySeeder dulu.');

            return;
        }

        // [prodi_code, nidn, nama, edu, fungsional, struktural, role_extra]
        $rows = [
            // Fakultas Teknik
            ['IF',  '0301017001', 'Dr. Ahmad Wirawan, M.Kom',   EducationLevel::S3, FunctionalPosition::LektorKepala, StructuralPosition::Dekan,   RoleEnum::Dekan],
            ['IF',  '0302027002', 'Dr. Siti Nurhaliza, M.T.',   EducationLevel::S3, FunctionalPosition::Lektor,        StructuralPosition::Kaprodi, RoleEnum::Kaprodi],
            ['IF',  '0303037003', 'Budi Santoso, M.Kom',        EducationLevel::S2, FunctionalPosition::AsistenAhli,   StructuralPosition::None,    RoleEnum::Dosen],
            ['IF',  '0304047004', 'Rina Andriani, M.Cs',        EducationLevel::S2, FunctionalPosition::AsistenAhli,   StructuralPosition::None,    RoleEnum::Dosen],
            ['SI',  '0305057005', 'Dr. Bambang Hartono, M.M',   EducationLevel::S3, FunctionalPosition::Lektor,        StructuralPosition::Kaprodi, RoleEnum::Kaprodi],
            ['SI',  '0306067006', 'Hendra Gunawan, M.Cs',       EducationLevel::S2, FunctionalPosition::AsistenAhli,   StructuralPosition::None,    RoleEnum::Dosen],
            ['TE',  '0307077007', 'Dr. Eko Prasetyo, S.T., M.T',EducationLevel::S3, FunctionalPosition::Lektor,        StructuralPosition::Kaprodi, RoleEnum::Kaprodi],
            // Fakultas FEB
            ['MNJ', '0308087008', 'Dr. Linda Permata, M.M',     EducationLevel::S3, FunctionalPosition::LektorKepala, StructuralPosition::Dekan,   RoleEnum::Dekan],
            ['MNJ', '0309097009', 'Dr. Yudi Pratama, M.M',      EducationLevel::S3, FunctionalPosition::Lektor,        StructuralPosition::Kaprodi, RoleEnum::Kaprodi],
            ['AKT', '0310107010', 'Dr. Maya Sari, S.E., M.Ak',  EducationLevel::S3, FunctionalPosition::Lektor,        StructuralPosition::Kaprodi, RoleEnum::Kaprodi],
            // Fakultas FISIP
            ['KOM', '0311117011', 'Dr. Fadli Rachman, M.Si',    EducationLevel::S3, FunctionalPosition::LektorKepala, StructuralPosition::Dekan,   RoleEnum::Dekan],
            ['KOM', '0312127012', 'Dr. Tania Putri, M.I.Kom',   EducationLevel::S3, FunctionalPosition::Lektor,        StructuralPosition::Kaprodi, RoleEnum::Kaprodi],
        ];

        foreach ($rows as [$prodiCode, $nidn, $name, $edu, $func, $struct, $extraRole]) {
            $prodi = StudyProgram::where('code', $prodiCode)->first();
            if (! $prodi) {
                continue;
            }

            $user = User::firstOrCreate(
                ['email' => "{$nidn}@univercity.test"],
                [
                    'username' => $nidn,
                    'name' => $name,
                    'full_name' => $name,
                    'user_type' => UserType::Lecturer,
                    'password' => Hash::make('password'),
                    'is_active' => true,
                    'email_verified_at' => now(),
                ],
            );

            foreach ([RoleEnum::Dosen->value, $extraRole->value] as $role) {
                if (! $user->hasRole($role)) {
                    $user->assignRole($role);
                }
            }

            Lecturer::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'nidn' => $nidn,
                    'study_program_id' => $prodi->id,
                    'functional_position' => $func->value,
                    'structural_position' => $struct->value,
                    'education_level' => $edu->value,
                    'employment_status' => EmploymentStatus::TetapYayasan->value,
                    'start_date' => '2020-08-01',
                    'is_active' => true,
                ],
            );
        }

        $this->command->info('✓ '.Lecturer::count().' Dosen dibuat. Dekan & Kaprodi otomatis ter-set via Observer.');
    }
}
