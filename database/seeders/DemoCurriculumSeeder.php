<?php

namespace Database\Seeders;

use App\Enums\Academic\CourseType;
use App\Models\Course;
use App\Models\Curriculum;
use App\Models\GradeSchema;
use App\Models\Prerequisite;
use App\Models\StudyProgram;
use Illuminate\Database\Seeder;

/**
 * Seeder demo: Kurikulum S1 Informatika lengkap (mirip kampus Indonesia).
 *
 * Mengisi:
 *  - Skema Nilai standar (A-E)
 *  - 1 Kurikulum aktif untuk Prodi Informatika
 *  - 50+ Mata Kuliah selama 8 semester (~144 SKS)
 *  - Prasyarat antar MK
 *
 * Cara pakai:
 *   php artisan db:seed --class=DemoCurriculumSeeder
 *
 * Prasyarat: Prodi dengan kode "IF" (atau name "informatika") sudah ada.
 */
class DemoCurriculumSeeder extends Seeder
{

    public function run(): void
    {
        $this->seedGradeSchemas();

        // Detail kurikulum (51 MK + prasyarat) hanya untuk Informatika.
        $informatika = StudyProgram::where('code', 'IF')->first();
        if ($informatika) {
            $curriculum = $this->seedCurriculum($informatika);
            $courses = $this->seedCourses($curriculum);
            $this->seedPrerequisites($courses);
        }

        // Untuk prodi lain: bikin shell kurikulum kosong supaya mahasiswa bisa di-assign.
        $others = StudyProgram::when($informatika, fn ($q) => $q->where('id', '!=', $informatika->id))->get();
        foreach ($others as $prodi) {
            Curriculum::firstOrCreate(
                ['study_program_id' => $prodi->id, 'code' => "KUR-{$prodi->code}-2026", 'version' => 'v1'],
                [
                    'name' => "Kurikulum {$prodi->name} 2026",
                    'effective_year' => 2026,
                    'total_sks_required' => 144,
                    'is_active' => true,
                    'is_archived' => false,
                ],
            );
        }

        $this->command->info('');
        $this->command->info('✓ Demo Kurikulum berhasil dibuat:');
        $this->command->table(
            ['Item', 'Jumlah'],
            [
                ['Skema Nilai', GradeSchema::count()],
                ['Kurikulum (semua prodi)', Curriculum::count()],
                ['Mata Kuliah (Informatika)', Course::count()],
                ['Prasyarat', Prerequisite::count()],
            ],
        );
        $this->command->warn('Catatan: hanya Prodi Informatika yang punya 51 MK detail. Prodi lain pakai kurikulum kosong — silakan isi MK manual.');
    }

    private function seedGradeSchemas(): void
    {
        $schemas = [
            ['letter' => 'A',  'min' =>  80, 'max' => 100,    'gp' => 4.00, 'desc' => 'Sangat Baik'],
            ['letter' => 'AB', 'min' =>  75, 'max' =>  79.99, 'gp' => 3.50, 'desc' => 'Baik Sekali'],
            ['letter' => 'B',  'min' =>  70, 'max' =>  74.99, 'gp' => 3.00, 'desc' => 'Baik'],
            ['letter' => 'BC', 'min' =>  65, 'max' =>  69.99, 'gp' => 2.50, 'desc' => 'Cukup Baik'],
            ['letter' => 'C',  'min' =>  60, 'max' =>  64.99, 'gp' => 2.00, 'desc' => 'Cukup'],
            ['letter' => 'D',  'min' =>  50, 'max' =>  59.99, 'gp' => 1.00, 'desc' => 'Kurang'],
            ['letter' => 'E',  'min' =>   0, 'max' =>  49.99, 'gp' => 0.00, 'desc' => 'Gagal'],
        ];

        foreach ($schemas as $row) {
            GradeSchema::firstOrCreate(
                ['letter' => $row['letter']],
                [
                    'min_score' => $row['min'],
                    'max_score' => $row['max'],
                    'grade_point' => $row['gp'],
                    'description' => $row['desc'],
                    'is_active' => true,
                ],
            );
        }
    }

    private function seedCurriculum(StudyProgram $program): Curriculum
    {
        return Curriculum::firstOrCreate(
            ['study_program_id' => $program->id, 'code' => 'KUR-IF-2026', 'version' => 'v1'],
            [
                'name' => 'Kurikulum S1 Informatika 2026',
                'effective_year' => 2026,
                'total_sks_required' => 144,
                'is_active' => true,
                'is_archived' => false,
            ],
        );
    }

    /** @return array<string, Course> */
    private function seedCourses(Curriculum $curriculum): array
    {
        $rows = [
            // ==================== SEMESTER 1 (20 SKS) ====================
            ['IF1101', 'Pendidikan Agama',                  2, 0, 1, CourseType::MKWU],
            ['IF1102', 'Pendidikan Pancasila',              2, 0, 1, CourseType::MKWU],
            ['IF1103', 'Bahasa Indonesia',                  2, 0, 1, CourseType::MKWU],
            ['IF1104', 'Kalkulus I',                        3, 0, 1, CourseType::Wajib],
            ['IF1105', 'Fisika Dasar',                      2, 1, 1, CourseType::Wajib],
            ['IF1106', 'Algoritma & Pemrograman',           2, 1, 1, CourseType::Wajib],
            ['IF1107', 'Pengantar Teknologi Informasi',     2, 0, 1, CourseType::Wajib],
            ['IF1108', 'Logika Informatika',                3, 0, 1, CourseType::Wajib],

            // ==================== SEMESTER 2 (21 SKS) ====================
            ['IF1201', 'Pendidikan Kewarganegaraan',        2, 0, 2, CourseType::MKWU],
            ['IF1202', 'Bahasa Inggris',                    2, 0, 2, CourseType::MKWU],
            ['IF1203', 'Kalkulus II',                       3, 0, 2, CourseType::Wajib],
            ['IF1204', 'Matematika Diskrit',                3, 0, 2, CourseType::Wajib],
            ['IF1205', 'Struktur Data',                     2, 1, 2, CourseType::Wajib],
            ['IF1206', 'Sistem Digital',                    2, 1, 2, CourseType::Wajib],
            ['IF1207', 'Pemrograman Berorientasi Objek',    2, 1, 2, CourseType::Wajib],

            // ==================== SEMESTER 3 (21 SKS) ====================
            ['IF2101', 'Statistika & Probabilitas',         3, 0, 3, CourseType::Wajib],
            ['IF2102', 'Aljabar Linier',                    3, 0, 3, CourseType::Wajib],
            ['IF2103', 'Basis Data',                        2, 1, 3, CourseType::Wajib],
            ['IF2104', 'Organisasi & Arsitektur Komputer',  3, 0, 3, CourseType::Wajib],
            ['IF2105', 'Pemrograman Web',                   2, 1, 3, CourseType::Wajib],
            ['IF2106', 'Sistem Operasi',                    3, 0, 3, CourseType::Wajib],
            ['IF2107', 'Metode Numerik',                    3, 0, 3, CourseType::Wajib],

            // ==================== SEMESTER 4 (21 SKS) ====================
            ['IF2201', 'Analisis & Desain Algoritma',       3, 0, 4, CourseType::Wajib],
            ['IF2202', 'Jaringan Komputer',                 2, 1, 4, CourseType::Wajib],
            ['IF2203', 'Rekayasa Perangkat Lunak',          3, 0, 4, CourseType::Wajib],
            ['IF2204', 'Pemrograman Mobile',                2, 1, 4, CourseType::Wajib],
            ['IF2205', 'Interaksi Manusia & Komputer',      3, 0, 4, CourseType::Wajib],
            ['IF2206', 'Teori Bahasa & Otomata',            3, 0, 4, CourseType::Wajib],
            ['IF2207', 'Grafika Komputer',                  2, 1, 4, CourseType::Wajib],

            // ==================== SEMESTER 5 (21 SKS) ====================
            ['IF3101', 'Kecerdasan Buatan',                 3, 0, 5, CourseType::Wajib],
            ['IF3102', 'Pemrograman Berbasis Framework',    2, 1, 5, CourseType::Wajib],
            ['IF3103', 'Manajemen Proyek Teknologi Informasi', 3, 0, 5, CourseType::Wajib],
            ['IF3104', 'Sistem Informasi',                  3, 0, 5, CourseType::Wajib],
            ['IF3105', 'Kriptografi & Keamanan Sistem',     3, 0, 5, CourseType::Wajib],
            ['IF3106', 'Metodologi Penelitian',             2, 0, 5, CourseType::Wajib],
            ['IF3107', 'Pilihan: Pengantar Data Science',   3, 0, 5, CourseType::Pilihan],
            ['IF3108', 'Pilihan: Game Development',         2, 1, 5, CourseType::Pilihan],

            // ==================== SEMESTER 6 (21 SKS) ====================
            ['IF3201', 'Data Mining',                       3, 0, 6, CourseType::Wajib],
            ['IF3202', 'Machine Learning',                  2, 1, 6, CourseType::Konsentrasi],
            ['IF3203', 'Pengolahan Citra Digital',          2, 1, 6, CourseType::Konsentrasi],
            ['IF3204', 'Cloud Computing',                   3, 0, 6, CourseType::Wajib],
            ['IF3205', 'Etika Profesi',                     2, 0, 6, CourseType::Wajib],
            ['IF3206', 'Pilihan: DevOps & Containerization', 3, 0, 6, CourseType::Pilihan],
            ['IF3207', 'Pilihan: Blockchain',               3, 0, 6, CourseType::Pilihan],
            ['IF3208', 'Pilihan: Sistem Tertanam (IoT)',    2, 1, 6, CourseType::Pilihan],

            // ==================== SEMESTER 7 (14 SKS) ====================
            ['IF4101', 'Kerja Praktik / Magang',            0, 4, 7, CourseType::Wajib],
            ['IF4102', 'Kuliah Kerja Nyata (KKN)',          0, 3, 7, CourseType::MKWU],
            ['IF4103', 'Seminar Proposal Skripsi',          2, 0, 7, CourseType::Wajib],
            ['IF4104', 'Kapita Selekta',                    2, 0, 7, CourseType::Wajib],
            ['IF4105', 'Pilihan: NLP (Natural Language Processing)', 3, 0, 7, CourseType::Pilihan],

            // ==================== SEMESTER 8 (6 SKS) ====================
            ['IF4201', 'Skripsi / Tugas Akhir',             6, 0, 8, CourseType::Wajib],
        ];

        $courses = [];
        foreach ($rows as [$code, $name, $theory, $practice, $sem, $type]) {
            $courses[$code] = Course::firstOrCreate(
                ['curriculum_id' => $curriculum->id, 'code' => $code],
                [
                    'name' => $name,
                    'sks_theory' => $theory,
                    'sks_practice' => $practice,
                    'sks_field' => 0,
                    'total_sks' => $theory + $practice,
                    'semester' => $sem,
                    'course_type' => $type->value,
                    'is_active' => true,
                ],
            );
        }

        return $courses;
    }

    /** @param array<string, Course> $courses */
    private function seedPrerequisites(array $courses): void
    {
        // Format: [course_code => [prereq_codes...]]
        $links = [
            'IF1203' => ['IF1104'],                            // Kalkulus II ← Kalkulus I
            'IF1205' => ['IF1106'],                            // Struktur Data ← Algoritma
            'IF1207' => ['IF1106'],                            // PBO ← Algoritma
            'IF2101' => ['IF1104'],                            // Statistika ← Kalkulus I
            'IF2102' => ['IF1104'],                            // Aljabar Linier ← Kalkulus I
            'IF2103' => ['IF1205'],                            // Basis Data ← Struktur Data
            'IF2104' => ['IF1206'],                            // OrgArs ← Sistem Digital
            'IF2105' => ['IF1207'],                            // Pemrograman Web ← PBO
            'IF2106' => ['IF2104'],                            // SO ← OrgArs
            'IF2201' => ['IF1205'],                            // Analisis Algoritma ← Struktur Data
            'IF2202' => ['IF2106'],                            // Jaringan ← SO
            'IF2203' => ['IF1207', 'IF2103'],                  // RPL ← PBO + Basis Data
            'IF2204' => ['IF1207'],                            // Mobile ← PBO
            'IF2205' => ['IF2105'],                            // IMK ← Web
            'IF2207' => ['IF1207', 'IF2102'],                  // Grafika ← PBO + Aljabar Linier
            'IF3101' => ['IF2101', 'IF2201'],                  // AI ← Statistika + AnalisisAlgo
            'IF3102' => ['IF2105'],                            // Framework ← Web
            'IF3104' => ['IF2103', 'IF2203'],                  // SI ← Basis Data + RPL
            'IF3105' => ['IF2106', 'IF2202'],                  // Kripto ← SO + Jaringan
            'IF3201' => ['IF3101'],                            // Data Mining ← AI
            'IF3202' => ['IF3101'],                            // ML ← AI
            'IF3203' => ['IF3101', 'IF2207'],                  // PCD ← AI + Grafika
            'IF3204' => ['IF2202', 'IF2106'],                  // Cloud ← Jaringan + SO
            'IF4101' => ['IF3104'],                            // Magang ← SI (min sem 5)
            'IF4103' => ['IF3106'],                            // Seminar Proposal ← Metodologi
            'IF4201' => ['IF4103'],                            // Skripsi ← Seminar Proposal
        ];

        foreach ($links as $courseCode => $prereqCodes) {
            if (! isset($courses[$courseCode])) {
                continue;
            }
            foreach ($prereqCodes as $prereqCode) {
                if (! isset($courses[$prereqCode])) {
                    continue;
                }
                Prerequisite::firstOrCreate(
                    [
                        'course_id' => $courses[$courseCode]->id,
                        'prerequisite_course_id' => $courses[$prereqCode]->id,
                    ],
                    [
                        'minimum_grade' => 'C',
                        'group_no' => 1,
                    ],
                );
            }
        }
    }
}
