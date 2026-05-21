<?php

namespace App\Observers;

use App\Enums\Academic\StructuralPosition;
use App\Models\Faculty;
use App\Models\Lecturer;

/**
 * Saat Faculty.dean_id berubah:
 *   - Lecturer LAMA (jika ada) → structural_position di-clear (None)
 *   - Lecturer BARU → structural_position di-set ke Dekan
 *
 * Pasangan dengan LecturerObserver untuk sinkronisasi 2 arah.
 */
class FacultyObserver
{
    public function updated(Faculty $faculty): void
    {
        if (! $faculty->wasChanged('dean_id')) {
            return;
        }

        $oldDeanUserId = $faculty->getOriginal('dean_id');
        $newDeanUserId = $faculty->dean_id;

        if ($oldDeanUserId && $oldDeanUserId !== $newDeanUserId) {
            Lecturer::where('user_id', $oldDeanUserId)
                ->where('structural_position', StructuralPosition::Dekan->value)
                ->update(['structural_position' => StructuralPosition::None->value]);
        }

        if ($newDeanUserId) {
            Lecturer::where('user_id', $newDeanUserId)
                ->update(['structural_position' => StructuralPosition::Dekan->value]);
        }
    }
}
