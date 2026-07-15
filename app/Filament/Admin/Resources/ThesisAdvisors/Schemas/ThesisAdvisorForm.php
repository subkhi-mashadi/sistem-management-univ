<?php

namespace App\Filament\Admin\Resources\ThesisAdvisors\Schemas;

use App\Enums\Thesis\ThesisAdvisorStatus;
use App\Models\Student;
use App\Models\ThesisAdvisor;
use App\Services\Thesis\ThesisEligibilityService;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ThesisAdvisorForm
{
    /** Form buat CREATE — pilih 1 dosen, lalu banyak mahasiswa (topik) sekaligus. */
    public static function configureCreate(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Tugaskan Pembimbing')
                    ->columnSpanFull()
                    ->columns(1)->components([
                        Select::make('lecturer_id')
                            ->label('Dosen Pembimbing')
                            ->relationship('lecturer', 'nidn')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->user?->name.' ('.$record->nidn.')')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('student_ids')
                            ->label('Mahasiswa Bimbingan')
                            ->options(fn () => app(ThesisEligibilityService::class)->eligibleStudents()
                                ->mapWithKeys(fn (Student $student) => [
                                    $student->id => $student->nim.' — '.$student->user->name,
                                ]))
                            ->multiple()
                            ->searchable()
                            ->required()
                            ->helperText('Cuma mahasiswa yang sedang KRS-kan mata kuliah Skripsi di semester aktif yang muncul. Kalau belum punya topik, sistem otomatis buatkan topik Draft kosong yang nanti diisi mahasiswa sendiri.'),
                    ]),
            ]);
    }

    /** Form buat EDIT — dosen & mahasiswa dikunci, cuma status & tanggal yang bisa diubah. */
    public static function configureEdit(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Pembimbing')
                    ->columnSpanFull()
                    ->columns(2)->components([
                        Placeholder::make('student_display')
                            ->label('Mahasiswa')
                            ->content(fn (?ThesisAdvisor $record) => $record?->thesisTopic?->student?->user?->name ?? '—'),
                        Placeholder::make('topic_display')
                            ->label('Judul Skripsi')
                            ->content(fn (?ThesisAdvisor $record) => $record?->thesisTopic?->title ?? '—'),
                        Placeholder::make('lecturer_display')
                            ->label('Dosen Pembimbing')
                            ->content(fn (?ThesisAdvisor $record) => $record?->lecturer?->user?->name ?? '—'),
                        DatePicker::make('assigned_at')
                            ->label('Ditugaskan')
                            ->required(),
                        Select::make('status')
                            ->label('Status Bimbingan')
                            ->options(ThesisAdvisorStatus::class)
                            ->helperText('Ubah ke Completed kalau mahasiswa sudah selesai skripsi, atau Replaced kalau ganti pembimbing.')
                            ->required(),
                    ]),
            ]);
    }
}
