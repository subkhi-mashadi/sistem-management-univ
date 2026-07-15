<?php

namespace App\Filament\Admin\Resources\CourseOfferings\RelationManagers;

use App\Models\Grade;
use App\Models\GradeSchema;
use App\Models\KrsItem;
use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GradesRelationManager extends RelationManager
{
    protected static string $relationship = 'grades';

    protected static ?string $title = 'Nilai Mahasiswa';

    public function form(Schema $schema): Schema
    {
        return $schema->columns(3)->components([
            Placeholder::make('student_name')
                ->label('Mahasiswa')
                ->content(fn ($record) => $record?->student?->user?->full_name
                    .' ('.$record?->student?->nim.')')
                ->columnSpanFull(),

            TextInput::make('attendance_score')
                ->label('Kehadiran')
                ->numeric()->minValue(0)->maxValue(100)
                ->columnSpan(1),
            TextInput::make('assignment_score')
                ->label('Tugas')
                ->numeric()->minValue(0)->maxValue(100)
                ->columnSpan(1),
            TextInput::make('mid_score')
                ->label('UTS')
                ->numeric()->minValue(0)->maxValue(100)
                ->columnSpan(1),
            TextInput::make('final_score')
                ->label('UAS')
                ->numeric()->minValue(0)->maxValue(100)
                ->columnSpan(1),
            TextInput::make('extra_score')
                ->label('Tambahan')
                ->numeric()->minValue(0)->maxValue(100)
                ->columnSpan(1),
            TextInput::make('total_score')
                ->label('Total (auto)')
                ->numeric()
                ->disabled()
                ->dehydrated()
                ->helperText('Auto-hitung saat disimpan.')
                ->columnSpan(1),

            TextInput::make('letter_grade')
                ->label('Huruf Mutu (auto)')
                ->disabled()
                ->dehydrated()
                ->columnSpan(1),
            TextInput::make('grade_point')
                ->label('Bobot (auto)')
                ->numeric()
                ->disabled()
                ->dehydrated()
                ->columnSpan(1),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->defaultSort(fn ($q) => $q->join('students', 'students.id', '=', 'grades.student_id')
                ->orderBy('students.nim'))
            ->columns([
                TextColumn::make('student.nim')->label('NIM')->searchable(),
                TextColumn::make('student.user.full_name')->label('Nama')->wrap()->searchable(),
                TextColumn::make('attendance_score')->label('Hadir')->alignCenter(),
                TextColumn::make('assignment_score')->label('Tugas')->alignCenter(),
                TextColumn::make('mid_score')->label('UTS')->alignCenter(),
                TextColumn::make('final_score')->label('UAS')->alignCenter(),
                TextColumn::make('extra_score')->label('Extra')->alignCenter()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('total_score')->label('Total')
                    ->alignCenter()->weight(\Filament\Support\Enums\FontWeight::Bold),
                TextColumn::make('letter_grade')->label('Mutu')
                    ->badge()->alignCenter(),
                TextColumn::make('grade_point')->label('Bobot')
                    ->alignCenter()
                    ->formatStateUsing(fn ($state) => $state ? number_format((float) $state, 2) : '—'),
                IconColumn::make('is_locked')->label('Lock')->boolean()->alignCenter(),
            ])
            ->headerActions([
                Action::make('init_grades')
                    ->label('Init Nilai Semua Mahasiswa')
                    ->icon('heroicon-o-plus-circle')
                    ->color('info')
                    ->requiresConfirmation()
                    ->modalDescription('Buat record nilai kosong untuk semua mahasiswa yang terdaftar di kelas ini (yang belum ada nilainya).')
                    ->action(function () {
                        $offering = $this->getOwnerRecord();
                        $existing = Grade::where('course_offering_id', $offering->id)
                            ->pluck('student_id')->all();

                        $krsItems = KrsItem::where('course_offering_id', $offering->id)
                            ->whereIn('status', ['Active'])
                            ->with('enrollment:id,student_id')
                            ->get();

                        $created = 0;
                        foreach ($krsItems as $item) {
                            $studentId = $item->enrollment?->student_id;
                            if (! $studentId || in_array($studentId, $existing, true)) {
                                continue;
                            }
                            Grade::create([
                                'student_id'        => $studentId,
                                'course_offering_id' => $offering->id,
                                'krs_item_id'       => $item->id,
                                'is_locked'         => false,
                            ]);
                            $created++;
                        }

                        Notification::make()->success()
                            ->title("{$created} record nilai berhasil dibuat.")
                            ->send();
                    }),

                Action::make('lock_all')
                    ->label('Lock Semua Nilai')
                    ->icon('heroicon-o-lock-closed')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Lock Semua Nilai')
                    ->modalDescription('Semua nilai yang sudah punya total_score akan dikunci. Setelah dikunci, GradeObserver akan otomatis recalc Transkrip mahasiswa.')
                    ->action(function () {
                        $offering   = $this->getOwnerRecord();
                        $facultyId  = $offering->course?->curriculum?->studyProgram?->faculty_id
                            ?? $offering->semester?->academicCalendar?->id; // fallback

                        $schemas = GradeSchema::where('is_active', true)
                            ->orderByDesc('min_score')
                            ->get();

                        $grades = Grade::where('course_offering_id', $offering->id)
                            ->where('is_locked', false)
                            ->whereNotNull('total_score')
                            ->orWhere(fn ($q) => $q->where('course_offering_id', $offering->id)
                                ->where('is_locked', false)
                                ->where(fn ($q2) => $q2
                                    ->whereNotNull('mid_score')
                                    ->orWhereNotNull('final_score')))
                            ->get();

                        $locked = 0;
                        foreach ($grades as $grade) {
                            $total = self::calcTotal($grade);
                            [$letter, $point] = self::resolveGrade($total, $schemas);

                            $grade->update([
                                'total_score'  => $total,
                                'letter_grade' => $letter,
                                'grade_point'  => $point,
                                'is_locked'    => true,
                                'locked_at'    => now(),
                                'locked_by'    => auth()->id(),
                            ]);
                            $locked++;
                        }

                        Notification::make()->success()
                            ->title("{$locked} nilai dikunci. Transkrip mahasiswa diperbarui otomatis.")
                            ->send();
                    }),
            ])
            ->recordActions([
                EditAction::make()
                    ->visible(fn ($record) => ! $record->is_locked)
                    ->mutateFormDataUsing(function (array $data) use (&$record): array {
                        $total = self::calcTotalFromData($data);
                        $schemas = GradeSchema::where('is_active', true)->orderByDesc('min_score')->get();
                        [$letter, $point] = self::resolveGrade($total, $schemas);
                        $data['total_score']  = $total;
                        $data['letter_grade'] = $letter;
                        $data['grade_point']  = $point;

                        return $data;
                    }),
            ]);
    }

    private static function calcTotal(Grade $grade): float
    {
        return self::calcTotalFromData([
            'attendance_score' => $grade->attendance_score,
            'assignment_score' => $grade->assignment_score,
            'mid_score'        => $grade->mid_score,
            'final_score'      => $grade->final_score,
            'extra_score'      => $grade->extra_score,
        ]);
    }

    private static function calcTotalFromData(array $data): float
    {
        // Default bobot: Hadir 10%, Tugas 20%, UTS 30%, UAS 35%, Extra 5%
        return round(
            ((float) ($data['attendance_score'] ?? 0)) * 0.10
            + ((float) ($data['assignment_score'] ?? 0)) * 0.20
            + ((float) ($data['mid_score']        ?? 0)) * 0.30
            + ((float) ($data['final_score']       ?? 0)) * 0.35
            + ((float) ($data['extra_score']       ?? 0)) * 0.05,
            2
        );
    }

    /** @return array{0: string, 1: float} */
    private static function resolveGrade(float $total, $schemas): array
    {
        foreach ($schemas as $schema) {
            if ($total >= (float) $schema->min_score && $total <= (float) $schema->max_score) {
                return [$schema->letter, (float) $schema->grade_point];
            }
        }

        return ['E', 0.0];
    }
}
