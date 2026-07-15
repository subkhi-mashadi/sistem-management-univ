<?php

namespace App\Services\Thesis;

use App\Enums\Thesis\ThesisAdvisorStatus;
use App\Enums\Thesis\ThesisTopicStatus;
use App\Models\Logbook;
use App\Models\Student;
use App\Models\ThesisTopic;
use RuntimeException;

class LogbookService
{
    public function create(Student $student, ThesisTopic $topic, array $data): Logbook
    {
        if ($topic->student_id !== $student->id) {
            throw new RuntimeException('Topik ini bukan milik kamu.');
        }

        if ($topic->status !== ThesisTopicStatus::Approved) {
            throw new RuntimeException('Logbook hanya bisa diisi untuk topik yang sudah Approved.');
        }

        $advisor = $topic->advisors()
            ->where('status', ThesisAdvisorStatus::Active)
            ->orderBy('advisor_order')
            ->first();

        if (! $advisor) {
            throw new RuntimeException('Topik ini belum punya dosen pembimbing aktif. Hubungi Kaprodi.');
        }

        return Logbook::create([
            'thesis_topic_id' => $topic->id,
            'student_id' => $student->id,
            'lecturer_id' => $advisor->lecturer_id,
            'session_date' => $data['session_date'],
            'duration_minutes' => $data['duration_minutes'] ?? 60,
            'topic_discussed' => $data['topic_discussed'],
            'progress_summary' => $data['progress_summary'] ?? null,
            'is_verified' => false,
        ]);
    }

    public function verify(Logbook $logbook, ?string $feedback = null): Logbook
    {
        if ($logbook->is_verified) {
            throw new RuntimeException('Logbook ini sudah diverifikasi.');
        }

        $logbook->is_verified = true;
        $logbook->verified_at = now();
        $logbook->lecturer_feedback = $feedback;
        $logbook->save();

        return $logbook->fresh();
    }
}
