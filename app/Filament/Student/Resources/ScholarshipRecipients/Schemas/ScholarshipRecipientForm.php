<?php

namespace App\Filament\Student\Resources\ScholarshipRecipients\Schemas;

use App\Models\Scholarship;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ScholarshipRecipientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Ajukan Beasiswa')
                    ->columnSpanFull()
                    ->columns(1)
                    ->components([
                        Select::make('scholarship_id')
                            ->label('Jenis Beasiswa')
                            ->options(Scholarship::query()->where('is_active', true)->pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->helperText('Diajukan untuk semester aktif berjalan. Jumlah beasiswa dihitung otomatis oleh admin saat disetujui.'),
                    ]),
            ]);
    }
}
