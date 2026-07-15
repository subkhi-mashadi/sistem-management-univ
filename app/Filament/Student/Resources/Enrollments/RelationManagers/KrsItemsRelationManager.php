<?php

namespace App\Filament\Student\Resources\Enrollments\RelationManagers;

use App\Enums\Finance\InvoiceStatus;
use App\Enums\Krs\EnrollmentStatus;
use App\Enums\Krs\KrsItemStatus;
use App\Models\CourseOffering;
use App\Models\Grade;
use App\Models\Invoice;
use App\Models\Prerequisite;
use App\Models\Schedule;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Exceptions\Halt;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KrsItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $title = 'Mata Kuliah Diambil';

    public function form(Schema $schema): Schema
    {
        $enrollment = $this->getOwnerRecord();
        $semesterId = $enrollment->semester_id;
        $alreadyTakenIds = $enrollment->items()->pluck('course_offering_id')->all();

        return $schema
            ->columns(1)
            ->components([
                Select::make('course_offering_id')
                    ->label('Penawaran MK')
                    ->options(fn () => CourseOffering::query()
                        ->where('semester_id', $semesterId)
                        ->where('is_open', true)
                        ->whereNotIn('id', $alreadyTakenIds)
                        ->with('course')
                        ->get()
                        ->mapWithKeys(fn (CourseOffering $o) => [
                            $o->id => ($o->course?->code ?? '?')
                                .' — '.($o->course?->name ?? 'MK')
                                .' (Kelas '.$o->class_code.', '.($o->course?->total_sks ?? 0).' SKS)',
                        ])
                        ->all())
                    ->getOptionLabelUsing(function ($value): ?string {
                        $o = CourseOffering::with('course')->find($value);
                        if (! $o) {
                            return null;
                        }

                        return ($o->course?->code ?? '?').' — '.($o->course?->name ?? 'MK').' (Kelas '.$o->class_code.')';
                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    ->helperText('Hanya menampilkan MK semester aktif yang belum Anda ambil & masih buka.'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('courseOffering.course.code')
                    ->label('Kode MK')
                    ->badge(),
                TextColumn::make('courseOffering.course.name')
                    ->label('Mata Kuliah')
                    ->wrap(),
                TextColumn::make('courseOffering.class_code')
                    ->label('Kelas')
                    ->badge(),
                TextColumn::make('courseOffering.course.total_sks')
                    ->label('SKS')
                    ->alignCenter(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah MK')
                    ->icon('heroicon-o-plus')
                    ->visible(fn () => self::canModify($this->getOwnerRecord()))
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['status'] = KrsItemStatus::Active->value;

                        return $data;
                    })
                    ->before(function (array $data) {
                        $enrollment = $this->getOwnerRecord();
                        $offering = CourseOffering::with(['course.prerequisites', 'schedules'])->find($data['course_offering_id']);

                        if (! $offering) {
                            return;
                        }

                        $studentId = $enrollment->student_id;

                        $this->checkTunggakan($studentId, $enrollment->semester_id);

                        $this->checkMaxSks($enrollment, $offering);

                        $this->checkPrerequisites($studentId, $offering);

                        $this->checkScheduleConflict($enrollment, $offering);
                    }),
            ])
            ->recordActions([
                DeleteAction::make()
                    ->label('Drop')
                    ->visible(fn () => self::canModify($this->getOwnerRecord())),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn () => self::canModify($this->getOwnerRecord())),
                ]),
            ]);
    }

    private function checkTunggakan(int $studentId, int $semesterId): void
    {
        $hasUnpaid = Invoice::where('student_id', $studentId)
            ->where('semester_id', $semesterId)
            ->whereIn('status', [InvoiceStatus::Unpaid->value, InvoiceStatus::Overdue->value])
            ->exists();

        if ($hasUnpaid) {
            Notification::make()
                ->danger()
                ->title('Ada tunggakan')
                ->body('Anda memiliki tagihan yang belum lunas semester ini. Selesaikan pembayaran terlebih dahulu.')
                ->persistent()
                ->send();

            throw new Halt;
        }
    }

    private function checkMaxSks($enrollment, CourseOffering $offering): void
    {
        $newSks = (int) ($offering->course?->total_sks ?? 0);
        $currentSks = (int) $enrollment->total_sks_taken;
        $maxSks = (int) $enrollment->max_sks;

        if ($currentSks + $newSks > $maxSks) {
            Notification::make()
                ->danger()
                ->title('Melebihi batas SKS')
                ->body("Total akan jadi {$currentSks} + {$newSks} = "
                    .($currentSks + $newSks)." SKS, melebihi batas {$maxSks} SKS. "
                    .'Drop MK lain dulu atau minta kelonggaran dari Dosen Wali.')
                ->persistent()
                ->send();

            throw new Halt;
        }
    }

    private function checkPrerequisites(int $studentId, CourseOffering $offering): void
    {
        $courseId = $offering->course_id;
        $prerequisites = Prerequisite::where('course_id', $courseId)->get();

        foreach ($prerequisites as $prereq) {
            $passed = Grade::where('student_id', $studentId)
                ->whereHas('courseOffering', fn ($q) => $q->where('course_id', $prereq->prerequisite_course_id))
                ->where('is_locked', true)
                ->where('letter_grade', '!=', 'E')
                ->when($prereq->minimum_grade, function ($q) use ($prereq) {
                    // grade_point harus >= minimum_grade jika diset
                    $q->where('grade_point', '>=', $prereq->minimum_grade);
                })
                ->exists();

            if (! $passed) {
                $prereqCourseName = $prereq->prerequisiteCourse?->name ?? 'MK prasyarat';
                Notification::make()
                    ->danger()
                    ->title('Prasyarat belum terpenuhi')
                    ->body("Anda belum lulus \"{$prereqCourseName}\" yang diperlukan sebagai prasyarat MK ini.")
                    ->persistent()
                    ->send();

                throw new Halt;
            }
        }
    }

    private function checkScheduleConflict($enrollment, CourseOffering $offering): void
    {
        $newSchedules = $offering->schedules;
        if ($newSchedules->isEmpty()) {
            return;
        }

        // Kumpulkan semua jadwal dari MK yang sudah ada di KRS
        $existingOfferingIds = $enrollment->items()->pluck('course_offering_id')->all();
        $existingSchedules = Schedule::whereIn('course_offering_id', $existingOfferingIds)->get();

        foreach ($newSchedules as $new) {
            foreach ($existingSchedules as $existing) {
                if ($existing->day_of_week !== $new->day_of_week) {
                    continue;
                }

                // Cek overlap waktu: [new.start, new.end] vs [existing.start, existing.end]
                $overlap = $new->start_time < $existing->end_time
                    && $new->end_time > $existing->start_time;

                if ($overlap) {
                    $conflictOffering = CourseOffering::with('course')->find($existing->course_offering_id);
                    $conflictName = $conflictOffering?->course?->name ?? 'MK lain';
                    Notification::make()
                        ->danger()
                        ->title('Jadwal bentrok')
                        ->body("MK ini bentrok jadwalnya dengan \"{$conflictName}\" yang sudah ada di KRS Anda ({$existing->day_of_week->value}, {$existing->start_time}–{$existing->end_time}).")
                        ->persistent()
                        ->send();

                    throw new Halt;
                }
            }
        }
    }

    private static function canModify($enrollment): bool
    {
        $status = $enrollment->status instanceof EnrollmentStatus
            ? $enrollment->status->value
            : $enrollment->status;

        return in_array($status, [EnrollmentStatus::Draft->value, EnrollmentStatus::Rejected->value], true);
    }
}
