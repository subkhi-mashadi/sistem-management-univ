<?php

namespace App\Filament\Admin\Resources\Refunds\Schemas;

use App\Enums\Finance\RefundReason;
use App\Enums\Finance\RefundStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RefundForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama')
                    ->columnSpanFull()
                    ->columns(2)->components([
                        TextInput::make('refund_number')
                            ->label('Nomor Refund')
                            ->required(),
                        Select::make('payment_id')
                            ->label('Pembayaran')
                            ->relationship('payment', 'payment_number')->searchable()->preload(),
                        Select::make('student_id')
                            ->label('Mahasiswa')
                            ->relationship('student', 'nim')->searchable()->preload()
                            ->required(),
                        TextInput::make('amount')
                            ->label('Jumlah')
                            ->required()
                            ->numeric(),
                        Select::make('reason')
                            ->label('Alasan')
                            ->options(RefundReason::class)
                            ->required(),
                        Select::make('status')
                            ->label('Status')
                            ->options(RefundStatus::class)
                            ->default('Requested')
                            ->required(),
                        TextInput::make('approved_by')
                            ->label('Disetujui Oleh')
                            ->numeric(),
                        DateTimePicker::make('approved_at')
                            ->label('Tanggal Disetujui'),
                        DateTimePicker::make('disbursed_at')
                            ->label('Tanggal Pencairan'),
                        TextInput::make('bank_account')
                            ->label('Nomor Rekening'),
                        Textarea::make('notes')
                            ->label('Catatan')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
