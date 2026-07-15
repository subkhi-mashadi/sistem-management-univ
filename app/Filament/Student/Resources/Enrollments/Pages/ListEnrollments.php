<?php

namespace App\Filament\Student\Resources\Enrollments\Pages;

use App\Filament\Student\Resources\Enrollments\EnrollmentResource;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\Semester;
use App\Models\Student;
use App\Models\Transcript;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEnrollments extends ListRecords
{
    protected static string $resource = EnrollmentResource::class;

    protected function getHeaderActions(): array
    {
        $studentId       = auth()->user()?->student?->id;
        $activeSemester  = Semester::where('is_active', true)->first();

        $alreadyHasKrs = $activeSemester && Enrollment::where('student_id', $studentId)
            ->where('semester_id', $activeSemester->id)
            ->exists();

        return [
            Action::make('cetak_transkrip')
                ->label('Cetak Transkrip')
                ->icon('heroicon-o-document-chart-bar')
                ->color('gray')
                ->action(function () {
                    $student = auth()->user()->student;
                    if (! $student) {
                        return;
                    }

                    $student->load([
                        'user',
                        'studyProgram.faculty',
                        'advisor.user',
                    ]);

                    // Semua enrollment Approved/Locked, urut semester
                    $enrollments = Enrollment::with([
                        'semester',
                        'items.courseOffering.course',
                    ])
                        ->where('student_id', $student->id)
                        ->whereIn('status', ['Approved', 'Locked'])
                        ->get()
                        ->sortBy('semester.id');

                    $semesterData = $enrollments->map(function (Enrollment $enrollment) use ($student) {
                        $transcript = Transcript::where('student_id', $student->id)
                            ->where('semester_id', $enrollment->semester_id)
                            ->first();

                        $items = $enrollment->items->map(function ($item) use ($student) {
                            $grade = Grade::where('student_id', $student->id)
                                ->where('krs_item_id', $item->id)
                                ->where('is_locked', true)
                                ->first();

                            return [
                                'course'     => $item->courseOffering?->course,
                                'class_code' => $item->courseOffering?->class_code ?? '—',
                                'grade'      => $grade,
                            ];
                        });

                        return [
                            'enrollment' => $enrollment,
                            'transcript' => $transcript,
                            'items'      => $items,
                        ];
                    });

                    $lastTranscript      = Transcript::where('student_id', $student->id)
                        ->orderByDesc('semester_id')->first();
                    $lastAcademicStatus  = $lastTranscript?->academic_status?->value ?? null;

                    $nim = $student->nim;
                    $pdf = Pdf::loadView('pdf.transkrip', compact('student', 'semesterData', 'lastAcademicStatus'))
                        ->setPaper('a4', 'portrait');

                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        'Transkrip-'.$nim.'.pdf',
                    );
                }),

            CreateAction::make()
                ->disabled($alreadyHasKrs)
                ->tooltip($alreadyHasKrs
                    ? 'KRS semester aktif ('.$activeSemester->name.') sudah dibuat.'
                    : null),
        ];
    }
}
