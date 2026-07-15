<?php

namespace App\Services\Thesis;

use App\Enums\Krs\KrsItemStatus;
use App\Models\Enrollment;
use App\Models\Student;
use Illuminate\Database\Eloquent\Builder;

/**
 * Cek kelayakan mahasiswa ajukan/dibimbing skripsi: mahasiswa harus sedang
 * KRS-kan mata kuliah Skripsi/Tugas Akhir (bukan mata kuliah lain yang cuma
 * menyinggung kata "skripsi", misal "Seminar Proposal Skripsi") di semester aktif.
 */
class ThesisEligibilityService
{
    public function isEligible(Student $student): bool
    {
        return $this->scopeEligible(
            Enrollment::query()->where('student_id', $student->id)
        )->exists();
    }

    /** @return \Illuminate\Support\Collection<int, Student> */
    public function eligibleStudents()
    {
        return Student::query()
            ->whereHas('enrollments', fn (Builder $q) => $this->scopeEligible($q))
            ->with('user')
            ->get();
    }

    protected function scopeEligible(Builder $enrollmentQuery): Builder
    {
        return $enrollmentQuery
            ->whereHas('semester', fn (Builder $q) => $q->where('is_active', true))
            ->whereHas('items', function (Builder $q) {
                $q->where('status', KrsItemStatus::Active)
                    ->whereHas('courseOffering.course', fn (Builder $c) => $c
                        ->where('name', 'like', 'Skripsi%')
                        ->orWhere('name', 'like', '%Tugas Akhir%'));
            });
    }
}
