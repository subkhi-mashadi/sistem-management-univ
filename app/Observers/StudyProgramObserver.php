<?php

namespace App\Observers;

use App\Enums\Academic\StructuralPosition;
use App\Models\Lecturer;
use App\Models\StudyProgram;

/**
 * Saat StudyProgram.head_id berubah:
 *   - Lecturer LAMA → structural_position di-clear (None)
 *   - Lecturer BARU → structural_position di-set ke Kaprodi
 *
 * Pasangan dengan LecturerObserver untuk sinkronisasi 2 arah.
 */
class StudyProgramObserver
{
    public function updated(StudyProgram $program): void
    {
        if (! $program->wasChanged('head_id')) {
            return;
        }

        $oldHeadUserId = $program->getOriginal('head_id');
        $newHeadUserId = $program->head_id;

        if ($oldHeadUserId && $oldHeadUserId !== $newHeadUserId) {
            Lecturer::where('user_id', $oldHeadUserId)
                ->where('structural_position', StructuralPosition::Kaprodi->value)
                ->update(['structural_position' => StructuralPosition::None->value]);
        }

        if ($newHeadUserId) {
            Lecturer::where('user_id', $newHeadUserId)
                ->update(['structural_position' => StructuralPosition::Kaprodi->value]);
        }
    }
}
