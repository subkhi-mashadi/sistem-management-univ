<?php

namespace App\Filament\Imports;

use App\Models\Faculty;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class FacultyImporter extends Importer
{
    protected static ?string $model = Faculty::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('code')
                ->label('Kode')
                ->rules(['nullable', 'string', 'max:20']),
            ImportColumn::make('name')
                ->label('Nama Fakultas')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            ImportColumn::make('pddikti_code')
                ->label('Kode PDDikti')
                ->rules(['nullable', 'string']),
            ImportColumn::make('description')
                ->label('Deskripsi')
                ->rules(['nullable', 'string']),
            ImportColumn::make('is_active')
                ->label('Aktif')
                ->boolean()
                ->rules(['nullable', 'boolean']),
        ];
    }

    public function resolveRecord(): ?Faculty
    {
        if (! empty($this->data['code'])) {
            return Faculty::firstOrNew(['code' => $this->data['code']]);
        }

        return new Faculty;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Import Fakultas selesai. '.Number::format($import->successful_rows).' baris berhasil diimport.';

        if ($failed = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failed).' baris gagal.';
        }

        return $body;
    }
}
