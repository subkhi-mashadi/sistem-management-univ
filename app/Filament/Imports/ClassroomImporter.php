<?php

namespace App\Filament\Imports;

use App\Models\Classroom;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class ClassroomImporter extends Importer
{
    protected static ?string $model = Classroom::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('code')
                ->label('Kode')
                ->rules(['nullable', 'string', 'max:50']),
            ImportColumn::make('name')
                ->label('Nama Ruang')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            ImportColumn::make('building')
                ->label('Gedung')
                ->rules(['nullable', 'string']),
            ImportColumn::make('floor')
                ->label('Lantai')
                ->rules(['nullable', 'string']),
            ImportColumn::make('capacity')
                ->label('Kapasitas')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('room_type')
                ->label('Jenis Ruang')
                ->requiredMapping()
                ->rules(['required', 'in:Kelas,Lab,Studio,Auditorium,Online']),
            ImportColumn::make('is_active')
                ->label('Aktif')
                ->boolean()
                ->rules(['nullable', 'boolean']),
        ];
    }

    public function resolveRecord(): ?Classroom
    {
        if (! empty($this->data['code'])) {
            return Classroom::firstOrNew(['code' => $this->data['code']]);
        }

        return new Classroom;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Import Ruang Kelas selesai. '.Number::format($import->successful_rows).' baris berhasil diimport.';

        if ($failed = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failed).' baris gagal.';
        }

        return $body;
    }
}
