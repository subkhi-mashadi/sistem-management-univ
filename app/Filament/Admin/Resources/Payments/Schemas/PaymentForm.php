<?php

namespace App\Filament\Admin\Resources\Payments\Schemas;

use App\Enums\Finance\PaymentMethod;
use App\Enums\Finance\PaymentStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('payment_number')
                    ->label('Nomor Pembayaran')
                    ->required(),
                Select::make('invoice_id')
                    ->label('Invoice')
                    ->relationship('invoice', 'id')
                    ->required(),
                Select::make('student_id')
                    ->label('Mahasiswa')
                    ->relationship('student', 'id')
                    ->required(),
                TextInput::make('amount')
                    ->label('Jumlah')
                    ->required()
                    ->numeric(),
                Select::make('payment_method')
                    ->label('Metode Bayar')
                    ->options(PaymentMethod::class)
                    ->required(),
                TextInput::make('bank_code')
                    ->label('Kode Bank'),
                TextInput::make('bank_reference')
                    ->label('Referensi Bank'),
                DateTimePicker::make('payment_date')
                    ->label('Tanggal Bayar')
                    ->required(),
                Select::make('status')
                    ->label('Status')
                    ->options(PaymentStatus::class)
                    ->default('Pending')
                    ->required(),
                DateTimePicker::make('reconciled_at')
                    ->label('Direkonsiliasi'),
                TextInput::make('raw_payload')
                    ->label('Payload'),
            ]);
    }
}
