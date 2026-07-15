<?php

namespace App\Services\Thesis;

use App\Enums\Thesis\ThesisAdvisorStatus;
use App\Enums\Thesis\ThesisTopicStatus;
use App\Models\Lecturer;
use App\Models\ThesisAdvisor;
use App\Models\ThesisTopic;
use RuntimeException;

class ThesisAdvisorAssignmentService
{
    /**
     * Tugaskan 1 dosen jadi pembimbing buat banyak mahasiswa sekaligus.
     * Mahasiswa yang belum punya ThesisTopic otomatis dibuatkan topik Draft
     * kosong (diisi judulnya belakangan oleh mahasiswa sendiri) — supaya
     * pembimbing bisa ditugaskan lebih awal, gak harus nunggu topik disetujui.
     * Mahasiswa yang sudah punya pembimbing aktif dari dosen yang sama dilewati.
     *
     * @param  array<int, int>  $studentIds
     */
    public function assign(Lecturer $lecturer, array $studentIds): ThesisAdvisor
    {
        $lastCreated = null;

        foreach ($studentIds as $studentId) {
            $topic = ThesisTopic::where('student_id', $studentId)
                ->latest()
                ->first();

            if (! $topic) {
                $topic = ThesisTopic::create([
                    'student_id' => $studentId,
                    'title' => '(Judul belum ditentukan)',
                    'status' => ThesisTopicStatus::Draft,
                ]);
            }

            $alreadyAssigned = ThesisAdvisor::where('thesis_topic_id', $topic->id)
                ->where('lecturer_id', $lecturer->id)
                ->exists();

            if ($alreadyAssigned) {
                continue;
            }

            $lastCreated = ThesisAdvisor::create([
                'thesis_topic_id' => $topic->id,
                'lecturer_id' => $lecturer->id,
                'advisor_order' => 1,
                'assigned_at' => now(),
                'status' => ThesisAdvisorStatus::Active,
            ]);
        }

        if (! $lastCreated) {
            throw new RuntimeException('Semua mahasiswa yang dipilih sudah punya pembimbing ini.');
        }

        return $lastCreated;
    }
}
