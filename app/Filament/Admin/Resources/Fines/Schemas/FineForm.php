<?php

namespace App\Filament\Admin\Resources\Fines\Schemas;

use App\Enums\Finance\FineType;
use App\Models\Invoice;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class FineForm
{
    private static function invoiceLabel(Invoice $i): string
    {
        $mhs = $i->student?->user?->full_name ?? $i->student?->user?->name ?? 'Mhs';

        return $i->invoice_number.' — '.$mhs.' (Total Rp '.number_format((float) $i->total_amount, 0, ',', '.').')';
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Denda')
                    ->columnSpanFull()
                    ->schema([
                        Select::make('invoice_id')
                            ->label('Invoice')
                            ->options(fn () => Invoice::query()
                                ->with('student.user:id,full_name,name')
                                ->orderByDesc('created_at')
                                ->limit(100)
                                ->get()
                                ->mapWithKeys(fn (Invoice $i) => [$i->id => self::invoiceLabel($i)])
                                ->all())
                            ->getOptionLabelUsing(function ($value): ?string {
                                $inv = Invoice::with('student.user:id,full_name,name')->find($value);

                                return $inv ? self::invoiceLabel($inv) : null;
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull(),

                        Select::make('type')
                            ->label('Tipe Denda')
                            ->options(FineType::class)
                            ->default('Late')
                            ->required()
                            ->columnSpan(1),

                        TextInput::make('amount')
                            ->label('Nominal Denda')
                            ->prefix('Rp')
                            ->numeric()
                            ->minValue(1)
                            ->required()
                            ->columnSpan(1),

                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->placeholder('Mis. Telat bayar UKT 5 hari')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Pembebasan (Opsional)')
                    ->description('Centang jika denda dibebaskan / dimaafkan.')
                    ->columnSpanFull()
                    ->schema([
                        Toggle::make('waived')
                            ->label('Bebaskan Denda')
                            ->inline(false)
                            ->live()
                            ->columnSpanFull(),

                        Textarea::make('waive_reason')
                            ->label('Alasan Pembebasan')
                            ->placeholder('Mis. Banding diterima, mahasiswa kondisi force majeure')
                            ->rows(2)
                            ->required(fn (Get $get) => (bool) $get('waived'))
                            ->visible(fn (Get $get) => (bool) $get('waived'))
                            ->columnSpanFull(),
                    ])
                    ->columns(1),
            ]);
    }
}
