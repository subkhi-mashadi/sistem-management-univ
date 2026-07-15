<?php

namespace Database\Seeders;

use App\Enums\EOffice\LetterRequestStatus;
use App\Enums\Rbac\Role as RoleEnum;
use App\Models\LetterRequest;
use App\Models\LetterTemplate;
use App\Models\Student;
use App\Models\Workflow;
use App\Models\WorkflowStep;
use App\Services\EOffice\LetterIssuanceService;
use App\Services\EOffice\LetterWorkflowService;
use App\Support\EOffice\LetterSystemFields;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

/**
 * Demo Layer 11 — E-Office (Surat).
 *
 * Membuat:
 *  - Workflow + WorkflowStep (Dosen Wali -> Kaprodi, atau Kaprodi saja)
 *  - LetterTemplate untuk 4 jenis surat umum
 *  - Contoh LetterRequest utk 3 mahasiswa pertama (Draft, In Progress, Issued)
 *    lewat LetterWorkflowService/LetterIssuanceService supaya nomor surat,
 *    approval, PDF, dan QR code konsisten dgn alur asli.
 *
 * Cara pakai: php artisan db:seed --class=DemoLetterSeeder
 * Prasyarat: RolePermissionSeeder (role Kaprodi), DemoStudentSeeder, DemoLecturerSeeder.
 */
class DemoLetterSeeder extends Seeder
{
    public function run(): void
    {
        $kaprodiRole = Role::firstWhere('name', RoleEnum::Kaprodi->value);

        if (! $kaprodiRole) {
            $this->command->error('Role Kaprodi belum ada. Jalankan RolePermissionSeeder dulu.');

            return;
        }

        $students = Student::with(['user', 'advisor.user'])
            ->whereHas('advisor.user')
            ->take(3)
            ->get();

        if ($students->isEmpty()) {
            $this->command->error('Belum ada Mahasiswa dgn Dosen Wali. Jalankan DemoStudentSeeder dulu.');

            return;
        }

        // WorkflowStep.approver_user_id statis (satu per step, bukan per pengajuan),
        // jadi step "Dosen Wali" di demo ini pakai dosen wali mahasiswa pertama
        // sebagai contoh approver tetap (bukan dosen wali dinamis per mahasiswa).
        $demoWaliUser = $students->first()->advisor->user;

        $workflows = $this->seedWorkflows($kaprodiRole, $demoWaliUser->id);
        $templates = $this->seedTemplates($workflows);
        $this->seedSampleRequests($students, $templates);

        $this->command->info('');
        $this->command->info('✓ Demo E-Office (Surat) berhasil dibuat:');
        $this->command->table(
            ['Item', 'Jumlah'],
            [
                ['Workflow', Workflow::count()],
                ['Workflow Step', WorkflowStep::count()],
                ['Template Surat', LetterTemplate::count()],
                ['Pengajuan Surat', LetterRequest::count()],
            ],
        );
    }

    /** @return array<string, Workflow> */
    private function seedWorkflows(Role $kaprodiRole, int $demoWaliUserId): array
    {
        $workflows = [];

        $wf1 = Workflow::firstOrCreate(
            ['code' => 'WF-SURAT-AKTIF'],
            [
                'name' => 'Approval Surat — Wali lalu Kaprodi',
                'description' => 'Dosen Wali approve dulu, lanjut Kaprodi.',
                'entity_type' => 'LetterRequest',
                'is_active' => true,
            ],
        );
        $wf1->steps()->firstOrCreate(
            ['step_order' => 1],
            ['name' => 'Persetujuan Dosen Wali', 'approver_user_id' => $demoWaliUserId, 'is_parallel' => false, 'can_reject' => true, 'sla_hours' => 48],
        );
        $wf1->steps()->firstOrCreate(
            ['step_order' => 2],
            ['name' => 'Persetujuan Kaprodi', 'approver_role_id' => $kaprodiRole->id, 'is_parallel' => false, 'can_reject' => true, 'sla_hours' => 72],
        );
        $workflows['WF-SURAT-AKTIF'] = $wf1;

        $wf2 = Workflow::firstOrCreate(
            ['code' => 'WF-SURAT-KAPRODI'],
            [
                'name' => 'Approval Surat — Kaprodi',
                'description' => 'Langsung ke Kaprodi, satu step.',
                'entity_type' => 'LetterRequest',
                'is_active' => true,
            ],
        );
        $wf2->steps()->firstOrCreate(
            ['step_order' => 1],
            ['name' => 'Persetujuan Kaprodi', 'approver_role_id' => $kaprodiRole->id, 'is_parallel' => false, 'can_reject' => true, 'sla_hours' => 72],
        );
        $workflows['WF-SURAT-KAPRODI'] = $wf2;

        return $workflows;
    }

    /**
     * @param  array<string, Workflow>  $workflows
     * @return array<string, LetterTemplate>
     */
    private function seedTemplates(array $workflows): array
    {
        $rows = [
            [
                'code' => 'TPL-AKTIF',
                'name' => 'Surat Keterangan Aktif Kuliah',
                'category' => 'Aktif Kuliah',
                'body' => 'Sehubungan dengan data akademik yang tercatat pada Sistem Informasi Akademik Universitas '
                    .'Univercity, dengan ini kami menerangkan bahwa mahasiswa yang identitasnya tercantum di bawah ini '
                    .'benar merupakan mahasiswa aktif Universitas Univercity dan mengikuti kegiatan akademik sebagaimana '
                    .'mestinya sesuai dengan ketentuan yang berlaku di lingkungan Universitas Univercity.',
                'closing_text' => 'Surat keterangan ini diterbitkan untuk digunakan sebagai bukti status keaktifan '
                    .'mahasiswa yang bersangkutan, guna keperluan administratif seperti pengurusan beasiswa, keringanan '
                    .'pajak orang tua/wali, pengurusan asuransi, atau keperluan lain yang memerlukan bukti status '
                    .'kemahasiswaan aktif.',
                'data_fields' => ['nama', 'nim', 'prodi', 'fakultas', 'angkatan', 'semester_aktif'],
                'custom_fields' => [],
                'requires_signature' => true,
                'workflow' => 'WF-SURAT-AKTIF',
            ],
            [
                'code' => 'TPL-CUTI',
                'name' => 'Surat Permohonan Cuti Akademik',
                'category' => 'Cuti',
                'body' => 'Sehubungan dengan kondisi yang dialami, dengan ini mahasiswa yang identitasnya tercantum di '
                    .'bawah ini mengajukan permohonan cuti akademik terhitung mulai semester berjalan.',
                'closing_text' => 'Selama masa cuti akademik berlaku, yang bersangkutan tidak diperkenankan mengikuti '
                    .'seluruh kegiatan akademik (perkuliahan, ujian, praktikum, dan bimbingan) namun tetap tercatat '
                    .'sebagai mahasiswa Universitas Univercity sesuai dengan ketentuan akademik yang berlaku. Masa cuti '
                    .'tidak diperhitungkan sebagai masa studi aktif. Demikian permohonan ini dibuat dengan sebenarnya '
                    .'untuk dapat dipertimbangkan dan diproses lebih lanjut oleh pihak yang berwenang.',
                'data_fields' => ['nama', 'nim', 'prodi', 'semester_aktif', 'dosen_wali'],
                'custom_fields' => [['key' => 'alasan', 'label' => 'Alasan Cuti']],
                'requires_signature' => true,
                'workflow' => 'WF-SURAT-AKTIF',
            ],
            [
                'code' => 'TPL-REKOM',
                'name' => 'Surat Rekomendasi',
                'category' => 'Rekomendasi',
                'body' => 'Berdasarkan catatan akademik dan penilaian dari pihak Program Studi, dengan ini kami '
                    .'memberikan rekomendasi kepada mahasiswa yang identitasnya tercantum di bawah ini sebagai mahasiswa '
                    .'yang memiliki kompetensi, integritas, dan rekam jejak akademik yang baik selama menempuh studi '
                    .'di Universitas Univercity.',
                'closing_text' => 'Kami berharap pihak yang menerima surat ini dapat mempertimbangkan yang bersangkutan '
                    .'sesuai dengan kebutuhan yang berlaku, dan kami siap memberikan keterangan tambahan apabila diperlukan.',
                'data_fields' => ['nama', 'nim', 'prodi', 'ipk'],
                'custom_fields' => [['key' => 'tujuan', 'label' => 'Tujuan Rekomendasi']],
                'requires_signature' => false,
                'workflow' => 'WF-SURAT-KAPRODI',
            ],
            [
                'code' => 'TPL-BEBAS-PUSTAKA',
                'name' => 'Surat Keterangan Bebas Pustaka',
                'category' => 'Bebas Pustaka',
                'body' => 'Berdasarkan hasil pemeriksaan pada sistem administrasi Perpustakaan Universitas Univercity, '
                    .'dengan ini kami menerangkan bahwa mahasiswa yang identitasnya tercantum di bawah ini telah '
                    .'mengembalikan seluruh koleksi pustaka yang dipinjam dan tidak memiliki tanggungan atau kewajiban '
                    .'apapun terhadap Perpustakaan Universitas Univercity, baik berupa peminjaman buku, denda '
                    .'keterlambatan, maupun kewajiban administratif lainnya.',
                'closing_text' => 'Surat keterangan ini diterbitkan sebagai salah satu syarat kelengkapan administrasi '
                    .'pengajuan yudisium/kelulusan yang bersangkutan, dan berlaku sesuai dengan masa berlaku yang '
                    .'tertera pada surat ini.',
                'data_fields' => ['nama', 'nim', 'prodi'],
                'custom_fields' => [],
                'requires_signature' => false,
                'workflow' => 'WF-SURAT-KAPRODI',
            ],
        ];

        $templates = [];

        foreach ($rows as $row) {
            $templates[$row['code']] = LetterTemplate::updateOrCreate(
                ['code' => $row['code']],
                [
                    'name' => $row['name'],
                    'category' => $row['category'],
                    'body' => $row['body'],
                    'closing_text' => $row['closing_text'],
                    'data_fields' => $row['data_fields'],
                    'custom_fields' => $row['custom_fields'],
                    'requires_signature' => $row['requires_signature'],
                    'default_workflow_id' => $workflows[$row['workflow']]->id,
                    'is_active' => true,
                ],
            );
        }

        return $templates;
    }

    /**
     * @param  \Illuminate\Support\Collection<int, Student>  $students
     * @param  array<string, LetterTemplate>  $templates
     */
    private function seedSampleRequests($students, array $templates): void
    {
        if (LetterRequest::count() > 0) {
            $this->command->info('Pengajuan Surat sudah ada, lewati contoh pengajuan.');

            return;
        }

        $workflowService = app(LetterWorkflowService::class);
        $issuanceService = app(LetterIssuanceService::class);

        // 1) Draft — belum disubmit.
        if ($students->count() >= 1) {
            $student = $students[0];
            $template = $templates['TPL-AKTIF'];
            LetterRequest::create([
                'letter_template_id' => $template->id,
                'requester_id' => $student->user_id,
                'student_id' => $student->id,
                'form_data' => $this->formData($template, $student),
                'status' => LetterRequestStatus::Draft,
            ]);
        }

        // 2) In Progress — sudah submit, menunggu approval step 1 (Dosen Wali).
        if ($students->count() >= 2) {
            $student = $students[1];
            $template = $templates['TPL-CUTI'];
            $letterRequest = LetterRequest::create([
                'letter_template_id' => $template->id,
                'requester_id' => $student->user_id,
                'student_id' => $student->id,
                'form_data' => $this->formData($template, $student, ['alasan' => 'Kondisi kesehatan']),
                'status' => LetterRequestStatus::Draft,
            ]);
            $workflowService->submit($letterRequest);
        }

        // 3) Issued — full siklus: submit -> approve semua step -> terbitkan.
        if ($students->count() >= 3) {
            $student = $students[2];
            $template = $templates['TPL-BEBAS-PUSTAKA'];
            $letterRequest = LetterRequest::create([
                'letter_template_id' => $template->id,
                'requester_id' => $student->user_id,
                'student_id' => $student->id,
                'form_data' => $this->formData($template, $student),
                'status' => LetterRequestStatus::Draft,
            ]);
            $letterRequest = $workflowService->submit($letterRequest);

            $kaprodiUser = Role::firstWhere('name', RoleEnum::Kaprodi->value)?->users()->first();

            if ($kaprodiUser) {
                $approval = $workflowService->pendingApprovalFor($letterRequest, $kaprodiUser);
                if ($approval) {
                    $letterRequest = $workflowService->approve($approval, $kaprodiUser, 'Disetujui (demo seeder)');
                }
            }

            if ($letterRequest->status === LetterRequestStatus::Approved) {
                $issuanceService->issue($letterRequest);
            }
        }
    }

    /**
     * Simulasikan alur asli: field sistem di-resolve otomatis, field custom diisi manual.
     *
     * @return array<string, string>
     */
    private function formData(LetterTemplate $template, Student $student, array $customValues = []): array
    {
        $auto = LetterSystemFields::resolve($template->data_fields ?? [], $student);

        $custom = collect($template->custom_fields ?? [])
            ->filter(fn ($field) => ! empty($field['key']))
            ->mapWithKeys(fn ($field) => [$field['key'] => $customValues[$field['key']] ?? 'keperluan administrasi'])
            ->all();

        return array_merge($auto, $custom);
    }
}
