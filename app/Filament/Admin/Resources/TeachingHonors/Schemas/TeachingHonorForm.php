<?php

namespace App\Filament\Admin\Resources\TeachingHonors\Schemas;

use App\Enums\Hris\TeachingHonorStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TeachingHonorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama')
                    ->columnSpanFull()
                    ->columns(2)->components([
                        Select::make('lecturer_id')
                            ->label('Dosen')
                            ->relationship('lecturer', 'id')
                            ->required(),
                        Select::make('class_session_id')
                            ->label('Sesi Kelas')
                            ->relationship('classSession', 'id'),
                        TextInput::make('payroll_period_id')
                            ->label('Periode Payroll')
                            ->numeric(),
                        TextInput::make('rate_per_sks')
                            ->label('Rate per SKS')
                            ->required()
                            ->numeric()
                            ->default(0.0),
                        TextInput::make('sks')
                            ->label('SKS')
                            ->required()
                            ->numeric()
                            ->default(0.0),
                        TextInput::make('meeting_count')
                            ->label('Jumlah Pertemuan')
                            ->required()
                            ->numeric()
                            ->default(1),
                        TextInput::make('amount')
                            ->label('Jumlah')
                            ->required()
                            ->numeric(),
                        Select::make('status')
                            ->label('Status')
                            ->options(TeachingHonorStatus::class)
                            ->default('Pending')
                            ->required(),
                        TextInput::make('approved_by')
                            ->label('Disetujui Oleh')
                            ->numeric(),
                        DateTimePicker::make('approved_at')
                            ->label('Tanggal Disetujui'),
                    ]),
            ]);
    }
}
