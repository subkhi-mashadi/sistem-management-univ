<?php

namespace App\Observers;

use App\Enums\Academic\StructuralPosition;
use App\Models\Faculty;
use App\Models\Lecturer;
use App\Models\StudyProgram;

/**
 * Sinkronisasi 2 arah:
 *  - Lecturer.structural_position = Dekan + prodi tertentu  →  Faculty.dean_id = lecturer.user_id
 *  - Lecturer.structural_position = Kaprodi + prodi tertentu →  StudyProgram.head_id = lecturer.user_id
 *  - Bila lecturer berubah jadi BUKAN Dekan/Kaprodi, kosongkan referensi lamanya.
 *
 * Bekerja sama dengan FacultyObserver & StudyProgramObserver supaya arah sebaliknya juga sinkron.
 */
class LecturerObserver
{
    public function saved(Lecturer $lecturer): void
    {
        if (! $lecturer->wasRecentlyCreated && ! $lecturer->wasChanged(['structural_position', 'study_program_id'])) {
            return;
        }

        $position = $lecturer->structural_position;
        $programId = $lecturer->study_program_id;
        $program = $programId ? StudyProgram::find($programId) : null;

        if ($position === StructuralPosition::Dekan && $program) {
            Faculty::whereKey($program->faculty_id)
                ->where(fn ($q) => $q->where('dean_id', '!=', $lecturer->user_id)->orWhereNull('dean_id'))
                ->update(['dean_id' => $lecturer->user_id]);
        } else {
            Faculty::where('dean_id', $lecturer->user_id)->update(['dean_id' => null]);
        }

        if ($position === StructuralPosition::Kaprodi && $program) {
            StudyProgram::whereKey($program->id)
                ->where(fn ($q) => $q->where('head_id', '!=', $lecturer->user_id)->orWhereNull('head_id'))
                ->update(['head_id' => $lecturer->user_id]);
        } else {
            StudyProgram::where('head_id', $lecturer->user_id)->update(['head_id' => null]);
        }
    }

    public function deleted(Lecturer $lecturer): void
    {
        Faculty::where('dean_id', $lecturer->user_id)->update(['dean_id' => null]);
        StudyProgram::where('head_id', $lecturer->user_id)->update(['head_id' => null]);
    }
}
