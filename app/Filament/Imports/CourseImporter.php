<?php

namespace App\Filament\Imports;

use App\Models\Course;
use App\Models\Curriculum;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class CourseImporter extends Importer
{
    protected static ?string $model = Course::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('code')
                ->label('Kode')
                ->rules(['nullable', 'string', 'max:50']),
            ImportColumn::make('name')
                ->label('Nama Mata Kuliah')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            ImportColumn::make('name_en')
                ->label('Nama (English)')
                ->rules(['nullable', 'string', 'max:255']),
            ImportColumn::make('curriculum_code')
                ->label('Kode Kurikulum')
                ->requiredMapping()
                ->rules(['required', 'string']),
            ImportColumn::make('sks_theory')
                ->label('SKS Teori')
                ->numeric()
                ->rules(['nullable', 'integer']),
            ImportColumn::make('sks_practice')
                ->label('SKS Praktik')
                ->numeric()
                ->rules(['nullable', 'integer']),
            ImportColumn::make('sks_field')
                ->label('SKS Lapangan')
                ->numeric()
                ->rules(['nullable', 'integer']),
            ImportColumn::make('total_sks')
                ->label('Total SKS')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('semester')
                ->label('Semester')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('course_type')
                ->label('Jenis MK')
                ->requiredMapping()
                ->rules(['required', 'in:Wajib,Pilihan,MKDU,MKWU,Konsentrasi']),
            ImportColumn::make('is_active')
                ->label('Aktif')
                ->boolean()
                ->rules(['nullable', 'boolean']),
        ];
    }

    public function resolveRecord(): ?Course
    {
        $curriculum = Curriculum::where('code', $this->data['curriculum_code'] ?? null)->first();
        $curriculumId = $curriculum?->id;
        $this->data['curriculum_id'] = $curriculumId;
        unset($this->data['curriculum_code']);

        foreach (['sks_theory', 'sks_practice', 'sks_field'] as $key) {
            if (! isset($this->data[$key]) || $this->data[$key] === '') {
                $this->data[$key] = 0;
            }
        }

        $record = ! empty($this->data['code'])
            ? Course::firstOrNew(['code' => $this->data['code']])
            : new Course;

        $record->curriculum_id = $curriculumId;

        return $record;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Import Mata Kuliah selesai. '.Number::format($import->successful_rows).' baris berhasil diimport.';

        if ($failed = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failed).' baris gagal.';
        }

        return $body;
    }
}
