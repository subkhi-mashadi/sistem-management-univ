<?php

namespace App\Observers;

use App\Enums\Krs\AcademicStatus;
use App\Models\Grade;
use App\Models\KrsItem;
use App\Models\Transcript;

/**
 * Saat nilai di-lock → otomatis recalc Transcript mahasiswa untuk semester berjalan.
 * IPS, IPK, jumlah SKS, dan status akademik (Normal/Peringatan/DO Risk) ter-update otomatis.
 */
class GradeObserver
{
    public function saved(Grade $grade): void
    {
        if (! $grade->is_locked) {
            return;
        }
        $this->recalculateTranscript($grade);
    }

    private function recalculateTranscript(Grade $grade): void
    {
        $krsItem = KrsItem::with('enrollment')->find($grade->krs_item_id);
        if (! $krsItem || ! $krsItem->enrollment) {
            return;
        }

        $semesterId = $krsItem->enrollment->semester_id;
        $studentId = $grade->student_id;

        $grades = Grade::where('student_id', $studentId)
            ->whereIn('krs_item_id', KrsItem::whereHas('enrollment', fn ($q) => $q->where('semester_id', $semesterId))->pluck('id'))
            ->where('is_locked', true)
            ->with('courseOffering.course')
            ->get();

        $sksAttempted = (int) $grades->sum(fn ($g) => $g->courseOffering?->course?->total_sks ?? 0);
        $sksAcquired = (int) $grades->filter(fn ($g) => ($g->grade_point ?? 0) >= 1)
            ->sum(fn ($g) => $g->courseOffering?->course?->total_sks ?? 0);
        $totalPoints = $grades->sum(fn ($g) => ($g->grade_point ?? 0) * ($g->courseOffering?->course?->total_sks ?? 0));
        $gpa = $sksAttempted > 0 ? round($totalPoints / $sksAttempted, 2) : 0.0;

        $status = match (true) {
            $gpa < 1.5 => AcademicStatus::DoRisk,
            $gpa < 2.0 => AcademicStatus::Peringatan,
            default => AcademicStatus::Normal,
        };

        Transcript::updateOrCreate(
            ['student_id' => $studentId, 'semester_id' => $semesterId],
            [
                'sks_attempted' => $sksAttempted,
                'sks_acquired' => $sksAcquired,
                'semester_gpa' => $gpa,
                'cumulative_gpa' => $gpa,
                'sks_cumulative' => $sksAcquired,
                'academic_status' => $status->value,
            ],
        );
    }
}
