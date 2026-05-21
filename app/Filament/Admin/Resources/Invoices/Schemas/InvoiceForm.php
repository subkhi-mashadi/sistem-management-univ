<?php

namespace App\Filament\Admin\Resources\Invoices\Schemas;

use App\Enums\Finance\InstallmentPlan;
use App\Enums\Finance\InvoiceStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama')
                    ->columnSpanFull()
                    ->columns(2)->components([
                        TextInput::make('invoice_number')
                            ->label('Nomor Invoice')
                            ->required(),
                        Select::make('student_id')
                            ->label('Mahasiswa')
                            ->relationship('student', 'nim')->searchable()->preload()
                            ->required(),
                        Select::make('semester_id')
                            ->label('Semester')
                            ->relationship('semester', 'name')
                            ->required(),
                        TextInput::make('virtual_account')
                            ->label('Virtual Account'),
                        TextInput::make('bank_code')
                            ->label('Kode Bank'),
                        DatePicker::make('issue_date')
                            ->label('Tanggal Terbit')
                            ->required(),
                        DatePicker::make('due_date')
                            ->label('Jatuh Tempo')
                            ->required(),
                        TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->required()
                            ->numeric()
                            ->default(0.0),
                        TextInput::make('discount_amount')
                            ->label('Diskon')
                            ->required()
                            ->numeric()
                            ->default(0.0),
                        TextInput::make('fine_amount')
                            ->label('Denda')
                            ->required()
                            ->numeric()
                            ->default(0.0),
                        TextInput::make('scholarship_amount')
                            ->label('Beasiswa')
                            ->required()
                            ->numeric()
                            ->default(0.0),
                        TextInput::make('total_amount')
                            ->label('Total')
                            ->required()
                            ->numeric()
                            ->default(0.0),
                        TextInput::make('paid_amount')
                            ->label('Sudah Dibayar')
                            ->required()
                            ->numeric()
                            ->default(0.0),
                        Select::make('status')
                            ->label('Status')
                            ->options(InvoiceStatus::class)
                            ->default('Unpaid')
                            ->required(),
                        Select::make('installment_plan')
                            ->label('Skema Cicilan')
                            ->options(InstallmentPlan::class)
                            ->default('Full')
                            ->required(),
                        Textarea::make('notes')
                            ->label('Catatan')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
