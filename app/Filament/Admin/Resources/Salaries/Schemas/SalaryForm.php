<?php

namespace App\Filament\Admin\Resources\Salaries\Schemas;

use App\Enums\Hris\SalaryStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SalaryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama')
                    ->columnSpanFull()
                    ->columns(2)->components([
                        TextInput::make('payroll_period_id')
                            ->label('Periode Payroll')
                            ->required()
                            ->numeric(),
                        Select::make('employee_id')
                            ->label('Pegawai')
                            ->relationship('employee', 'id')
                            ->required(),
                        TextInput::make('base_salary')
                            ->label('Gaji Pokok')
                            ->required()
                            ->numeric()
                            ->default(0.0),
                        TextInput::make('total_allowance')
                            ->label('Total Tunjangan')
                            ->required()
                            ->numeric()
                            ->default(0.0),
                        TextInput::make('teaching_honor')
                            ->label('Honor Mengajar')
                            ->required()
                            ->numeric()
                            ->default(0.0),
                        TextInput::make('other_income')
                            ->label('Pendapatan Lain')
                            ->required()
                            ->numeric()
                            ->default(0.0),
                        TextInput::make('gross_total')
                            ->label('Total Bruto')
                            ->required()
                            ->numeric()
                            ->default(0.0),
                        TextInput::make('bpjs_deduction')
                            ->label('Potongan BPJS')
                            ->required()
                            ->numeric()
                            ->default(0.0),
                        TextInput::make('tax_pph21')
                            ->label('PPh 21')
                            ->required()
                            ->numeric()
                            ->default(0.0),
                        TextInput::make('other_deduction')
                            ->label('Potongan Lain')
                            ->required()
                            ->numeric()
                            ->default(0.0),
                        TextInput::make('total_deduction')
                            ->label('Total Potongan')
                            ->required()
                            ->numeric()
                            ->default(0.0),
                        TextInput::make('net_total')
                            ->label('Total Netto')
                            ->required()
                            ->numeric()
                            ->default(0.0),
                        Select::make('status')
                            ->label('Status')
                            ->options(SalaryStatus::class)
                            ->default('Draft')
                            ->required(),
                        DateTimePicker::make('paid_at')
                            ->label('Dibayar Pada'),
                        TextInput::make('slip_pdf_url')
                            ->label('Slip PDF')
                            ->url(),
                    ]),
            ]);
    }
}
