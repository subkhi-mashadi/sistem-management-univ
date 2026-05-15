<?php

namespace App\Filament\Admin\Resources\EmploymentHistories\Schemas;

use App\Enums\Hris\EmploymentEventType;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class EmploymentHistoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('employee_id')
                    ->label('Pegawai')
                    ->relationship('employee', 'id')
                    ->required(),
                Select::make('event_type')
                    ->label('Jenis Event')
                    ->options(EmploymentEventType::class)
                    ->required(),
                DatePicker::make('effective_date')
                    ->label('Tanggal Berlaku')
                    ->required(),
                TextInput::make('from_value')
                    ->label('Nilai Lama'),
                TextInput::make('to_value')
                    ->label('Nilai Baru'),
                Textarea::make('notes')
                    ->label('Catatan')
                    ->columnSpanFull(),
            ]);
    }
}
