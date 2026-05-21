<?php

namespace App\Observers;

use App\Models\CourseOffering;
use App\Models\Enrollment;
use App\Models\KrsItem;

/**
 * Jaga konsistensi:
 *  - course_offering.enrolled_count = jumlah KrsItem aktif
 *  - enrollment.total_sks_taken = total SKS dari MK yang diambil
 *
 * Tanpa observer ini, admin/mahasiswa harus update manual setiap kali tambah/drop MK.
 */
class KrsItemObserver
{
    public function saved(KrsItem $item): void
    {
        $this->refresh($item);
    }

    public function deleted(KrsItem $item): void
    {
        $this->refresh($item);
    }

    private function refresh(KrsItem $item): void
    {
        $this->refreshOffering($item->course_offering_id);
        $this->refreshEnrollment($item->enrollment_id);
    }

    private function refreshOffering(int $offeringId): void
    {
        $offering = CourseOffering::find($offeringId);
        if (! $offering) {
            return;
        }
        $offering->updateQuietly([
            'enrolled_count' => KrsItem::where('course_offering_id', $offeringId)->count(),
        ]);
    }

    private function refreshEnrollment(int $enrollmentId): void
    {
        $enrollment = Enrollment::find($enrollmentId);
        if (! $enrollment) {
            return;
        }

        $totalSks = KrsItem::where('enrollment_id', $enrollmentId)
            ->join('course_offerings', 'course_offerings.id', '=', 'krs_items.course_offering_id')
            ->join('courses', 'courses.id', '=', 'course_offerings.course_id')
            ->sum('courses.total_sks');

        $enrollment->updateQuietly(['total_sks_taken' => $totalSks]);
    }
}
