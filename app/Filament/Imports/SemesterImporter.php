<?php

namespace App\Filament\Imports;

use App\Models\AcademicCalendar;
use App\Models\Semester;
use Carbon\Carbon;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class SemesterImporter extends Importer
{
    protected static ?string $model = Semester::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('code')
                ->label('Kode')
                ->rules(['nullable', 'string', 'max:50']),
            ImportColumn::make('name')
                ->label('Nama Semester')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            ImportColumn::make('academic_calendar_year')
                ->label('Tahun Akademik')
                ->requiredMapping()
                ->rules(['required', 'string']),
            ImportColumn::make('term')
                ->label('Term')
                ->requiredMapping()
                ->rules(['required', 'in:Ganjil,Genap,Pendek']),
            ImportColumn::make('start_date')
                ->label('Tanggal Mulai')
                ->requiredMapping()
                ->castStateUsing(fn ($state) => $state ? Carbon::parse($state) : null)
                ->rules(['required', 'date']),
            ImportColumn::make('end_date')
                ->label('Tanggal Selesai')
                ->requiredMapping()
                ->castStateUsing(fn ($state) => $state ? Carbon::parse($state) : null)
                ->rules(['required', 'date']),
            ImportColumn::make('krs_start')
                ->label('KRS Mulai')
                ->castStateUsing(fn ($state) => $state ? Carbon::parse($state) : null)
                ->rules(['nullable', 'date']),
            ImportColumn::make('krs_end')
                ->label('KRS Selesai')
                ->castStateUsing(fn ($state) => $state ? Carbon::parse($state) : null)
                ->rules(['nullable', 'date']),
            ImportColumn::make('lecture_start')
                ->label('Kuliah Mulai')
                ->castStateUsing(fn ($state) => $state ? Carbon::parse($state) : null)
                ->rules(['nullable', 'date']),
            ImportColumn::make('lecture_end')
                ->label('Kuliah Selesai')
                ->castStateUsing(fn ($state) => $state ? Carbon::parse($state) : null)
                ->rules(['nullable', 'date']),
            ImportColumn::make('uts_start')
                ->label('UTS Mulai')
                ->castStateUsing(fn ($state) => $state ? Carbon::parse($state) : null)
                ->rules(['nullable', 'date']),
            ImportColumn::make('uts_end')
                ->label('UTS Selesai')
                ->castStateUsing(fn ($state) => $state ? Carbon::parse($state) : null)
                ->rules(['nullable', 'date']),
            ImportColumn::make('uas_start')
                ->label('UAS Mulai')
                ->castStateUsing(fn ($state) => $state ? Carbon::parse($state) : null)
                ->rules(['nullable', 'date']),
            ImportColumn::make('uas_end')
                ->label('UAS Selesai')
                ->castStateUsing(fn ($state) => $state ? Carbon::parse($state) : null)
                ->rules(['nullable', 'date']),
            ImportColumn::make('grade_input_deadline')
                ->label('Batas Input Nilai')
                ->castStateUsing(fn ($state) => $state ? Carbon::parse($state) : null)
                ->rules(['nullable', 'date']),
            ImportColumn::make('is_active')
                ->label('Aktif')
                ->boolean()
                ->rules(['nullable', 'boolean']),
        ];
    }

    public function resolveRecord(): ?Semester
    {
        $calendarId = AcademicCalendar::where('academic_year', $this->data['academic_calendar_year'] ?? null)->value('id');
        $this->data['academic_calendar_id'] = $calendarId;
        unset($this->data['academic_calendar_year']);

        $record = ! empty($this->data['code'])
            ? Semester::firstOrNew(['code' => $this->data['code']])
            : new Semester;

        $record->academic_calendar_id = $calendarId;

        return $record;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Import Semester selesai. '.Number::format($import->successful_rows).' baris berhasil diimport.';

        if ($failed = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failed).' baris gagal.';
        }

        return $body;
    }
}
