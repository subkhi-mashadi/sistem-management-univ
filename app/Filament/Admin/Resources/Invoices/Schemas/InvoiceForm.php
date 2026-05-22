<?php

namespace App\Filament\Admin\Resources\Invoices\Schemas;

use App\Enums\Finance\InstallmentPlan;
use App\Enums\Finance\InvoiceStatus;
use App\Models\Student;
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
        $rupiahFormat = fn ($state): string => number_format((float) ($state ?? 0), 0, ',', '.');

        return $schema
            ->components([
                Section::make('Identitas Tagihan')
                    ->columnSpanFull()
                    ->columns(2)
                    ->components([
                        TextInput::make('invoice_number')
                            ->label('Nomor Invoice')
                            ->placeholder('Auto-generate: INV-{semester}-{urut5}')
                            ->disabled()
                            ->dehydrated(false),
                        Select::make('student_id')
                            ->label('Mahasiswa')
                            ->options(fn () => Student::query()
                                ->with('user:id,full_name,name')
                                ->get()
                                ->mapWithKeys(fn (Student $s) => [
                                    $s->id => $s->nim.' — '.($s->user?->full_name ?? $s->user?->name ?? 'Mahasiswa #'.$s->id),
                                ])
                                ->all())
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('semester_id')
                            ->label('Semester')
                            ->relationship('semester', 'name', fn ($query) => $query->orderByDesc('is_active'))
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('installment_plan')
                            ->label('Skema Cicilan')
                            ->options(InstallmentPlan::class)
                            ->default('Full')
                            ->required(),
                    ]),

                Section::make('Pembayaran (Virtual Account)')
                    ->columnSpanFull()
                    ->columns(2)
                    ->components([
                        TextInput::make('virtual_account')
                            ->label('Virtual Account')
                            ->placeholder('Auto: 8800 + 10 digit ID mahasiswa')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('bank_code')
                            ->label('Bank')
                            ->placeholder('BNI / BSI / Mandiri')
                            ->default('BNI'),
                        DatePicker::make('issue_date')
                            ->label('Tanggal Terbit')
                            ->default(now())
                            ->native(false)
                            ->required(),
                        DatePicker::make('due_date')
                            ->label('Jatuh Tempo')
                            ->default(now()->addDays(21))
                            ->native(false)
                            ->required(),
                    ]),

                Section::make('Rincian Nominal (Auto-Calculated)')
                    ->description('Otomatis dihitung dari Item Invoice & Payment. Tidak perlu input manual.')
                    ->columnSpanFull()
                    ->columns(3)
                    ->components([
                        TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated()
                            ->formatStateUsing($rupiahFormat),
                        TextInput::make('discount_amount')
                            ->label('Diskon')
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated()
                            ->formatStateUsing($rupiahFormat),
                        TextInput::make('scholarship_amount')
                            ->label('Beasiswa')
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated()
                            ->formatStateUsing($rupiahFormat),
                        TextInput::make('fine_amount')
                            ->label('Denda')
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated()
                            ->formatStateUsing($rupiahFormat),
                        TextInput::make('total_amount')
                            ->label('Total Tagihan')
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated()
                            ->formatStateUsing($rupiahFormat)
                            ->extraInputAttributes(['class' => 'font-bold text-primary-600']),
                        TextInput::make('paid_amount')
                            ->label('Sudah Dibayar')
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated()
                            ->formatStateUsing($rupiahFormat)
                            ->helperText(fn ($record) => $record
                                ? 'Sisa: Rp '.number_format((float) ($record->total_amount - $record->paid_amount), 0, ',', '.')
                                : null),
                    ]),

                Section::make('Status & Catatan')
                    ->columnSpanFull()
                    ->columns(2)
                    ->components([
                        Select::make('status')
                            ->label('Status')
                            ->options(InvoiceStatus::class)
                            ->default('Unpaid')
                            ->disabled()
                            ->dehydrated()
                            ->helperText('Auto-update via PaymentObserver: Unpaid → Partial → Paid.'),
                        Textarea::make('notes')
                            ->label('Catatan')
                            ->columnSpanFull()
                            ->rows(2),
                    ]),
            ]);
    }
}
