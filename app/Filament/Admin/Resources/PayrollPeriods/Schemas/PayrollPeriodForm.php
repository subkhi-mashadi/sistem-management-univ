<?php

namespace App\Filament\Admin\Resources\PayrollPeriods\Schemas;

use App\Enums\Hris\PayrollPeriodStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PayrollPeriodForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama')
                    ->columnSpanFull()
                    ->columns(2)->components([
                        TextInput::make('code')
                            ->label('Kode')
                            ->placeholder('Auto-generate')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('name')
                            ->label('Nama')
                            ->required(),
                        TextInput::make('month')
                            ->label('Bulan')
                            ->required()
                            ->numeric(),
                        TextInput::make('year')
                            ->label('Tahun')
                            ->required(),
                        DatePicker::make('period_start')
                            ->label('Mulai Periode')
                            ->required(),
                        DatePicker::make('period_end')
                            ->label('Akhir Periode')
                            ->required(),
                        DatePicker::make('cutoff_date')
                            ->label('Tanggal Cutoff'),
                        DatePicker::make('payment_date')
                            ->label('Tanggal Bayar'),
                        Select::make('status')
                            ->label('Status')
                            ->options(PayrollPeriodStatus::class)
                            ->default('Open')
                            ->required(),
                        DateTimePicker::make('closed_at')
                            ->label('Ditutup'),
                        TextInput::make('closed_by')
                            ->label('Ditutup Oleh')
                            ->numeric(),
                    ]),
            ]);
    }
}
