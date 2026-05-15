<?php

namespace App\Filament\Imports;

use App\Models\Curriculum;
use App\Models\StudyProgram;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class CurriculumImporter extends Importer
{
    protected static ?string $model = Curriculum::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('code')
                ->label('Kode')
                ->rules(['nullable', 'string', 'max:50']),
            ImportColumn::make('name')
                ->label('Nama Kurikulum')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            ImportColumn::make('study_program_code')
                ->label('Kode Program Studi')
                ->requiredMapping()
                ->rules(['required', 'string']),
            ImportColumn::make('version')
                ->label('Versi')
                ->requiredMapping()
                ->rules(['required', 'string']),
            ImportColumn::make('effective_year')
                ->label('Tahun Berlaku')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('total_sks_required')
                ->label('Total SKS')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('is_active')
                ->label('Aktif')
                ->boolean()
                ->rules(['nullable', 'boolean']),
        ];
    }

    public function resolveRecord(): ?Curriculum
    {
        $studyProgramId = StudyProgram::where('code', $this->data['study_program_code'] ?? null)->value('id');
        $this->data['study_program_id'] = $studyProgramId;
        unset($this->data['study_program_code']);

        $record = ! empty($this->data['code'])
            ? Curriculum::firstOrNew(['code' => $this->data['code']])
            : new Curriculum;

        $record->study_program_id = $studyProgramId;

        return $record;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Import Kurikulum selesai. '.Number::format($import->successful_rows).' baris berhasil diimport.';

        if ($failed = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failed).' baris gagal.';
        }

        return $body;
    }
}
