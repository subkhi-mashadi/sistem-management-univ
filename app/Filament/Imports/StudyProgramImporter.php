<?php

namespace App\Filament\Imports;

use App\Models\Faculty;
use App\Models\StudyProgram;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class StudyProgramImporter extends Importer
{
    protected static ?string $model = StudyProgram::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('code')
                ->label('Kode')
                ->rules(['nullable', 'string', 'max:20']),
            ImportColumn::make('name')
                ->label('Nama Program Studi')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            ImportColumn::make('faculty_code')
                ->label('Kode Fakultas')
                ->requiredMapping()
                ->rules(['required', 'string']),
            ImportColumn::make('degree_level')
                ->label('Jenjang')
                ->requiredMapping()
                ->rules(['required', 'in:D3,D4,S1,S2,S3,Profesi']),
            ImportColumn::make('pddikti_code')
                ->label('Kode PDDikti')
                ->rules(['nullable', 'string']),
            ImportColumn::make('accreditation')
                ->label('Akreditasi')
                ->rules(['nullable', 'in:A,B,C,Unggul,Baik Sekali,Baik,Tidak Terakreditasi']),
            ImportColumn::make('is_active')
                ->label('Aktif')
                ->boolean()
                ->rules(['nullable', 'boolean']),
        ];
    }

    public function resolveRecord(): ?StudyProgram
    {
        $facultyId = Faculty::where('code', $this->data['faculty_code'] ?? null)->value('id');
        $this->data['faculty_id'] = $facultyId;
        unset($this->data['faculty_code']);

        $record = ! empty($this->data['code'])
            ? StudyProgram::firstOrNew(['code' => $this->data['code']])
            : new StudyProgram;

        $record->faculty_id = $facultyId;

        return $record;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Import Program Studi selesai. '.Number::format($import->successful_rows).' baris berhasil diimport.';

        if ($failed = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failed).' baris gagal.';
        }

        return $body;
    }
}
