<?php

namespace App\Filament\Admin\Resources\Payments\Schemas;

use App\Enums\Finance\PaymentMethod;
use App\Enums\Finance\PaymentStatus;
use App\Models\Invoice;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class PaymentForm
{
    private static function invoiceLabel(Invoice $i): string
    {
        $mhs = $i->student?->user?->full_name ?? $i->student?->user?->name ?? 'Mhs';
        $sisa = (float) ($i->total_amount - $i->paid_amount);
        $sisaLabel = $sisa > 0
            ? 'Sisa Rp '.number_format($sisa, 0, ',', '.')
            : 'LUNAS';

        return $i->invoice_number.' — '.$mhs.' ('.$sisaLabel.')';
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Pembayaran')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('payment_number')
                            ->label('Nomor Pembayaran')
                            ->placeholder('Auto: PAY-YYYYMM-NNNNNN')
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpan(1),

                        DateTimePicker::make('payment_date')
                            ->label('Tanggal Bayar')
                            ->default(now())
                            ->seconds(false)
                            ->native(false)
                            ->required()
                            ->columnSpan(1),

                        Select::make('invoice_id')
                            ->label('Invoice')
                            ->options(fn () => Invoice::query()
                                ->with('student.user:id,full_name,name')
                                ->whereIn('status', ['Unpaid', 'Partial', 'Overdue'])
                                ->orderByDesc('created_at')
                                ->get()
                                ->mapWithKeys(fn (Invoice $i) => [
                                    $i->id => self::invoiceLabel($i),
                                ])
                                ->all())
                            ->getOptionLabelUsing(function ($value): ?string {
                                $inv = Invoice::with('student.user:id,full_name,name')->find($value);

                                return $inv ? self::invoiceLabel($inv) : null;
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Set $set, $state) {
                                if (! $state) {
                                    return;
                                }
                                $invoice = Invoice::find($state);
                                if ($invoice) {
                                    $set('amount', $invoice->total_amount - $invoice->paid_amount);
                                }
                            })
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Nominal & Metode')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('amount')
                            ->label('Nominal Pembayaran')
                            ->prefix('Rp')
                            ->numeric()
                            ->minValue(1)
                            ->required()
                            ->helperText('Boleh kurang dari sisa (= cicilan).')
                            ->columnSpan(1),

                        Select::make('payment_method')
                            ->label('Metode Bayar')
                            ->options(PaymentMethod::class)
                            ->default('VA')
                            ->required()
                            ->columnSpan(1),
                    ])
                    ->columns(2),

                Section::make('Verifikasi Bank')
                    ->description('Diisi setelah Admin Keuangan cek mutasi rekening / notifikasi VA.')
                    ->columnSpanFull()
                    ->schema([
                        Select::make('bank_code')
                            ->label('Bank Pengirim')
                            ->options([
                                'BNI' => 'BNI',
                                'BSI' => 'BSI (Bank Syariah Indonesia)',
                                'MANDIRI' => 'Mandiri',
                                'BCA' => 'BCA',
                                'BRI' => 'BRI',
                                'PERMATA' => 'Permata',
                                'CIMB' => 'CIMB Niaga',
                                'LAINNYA' => 'Lainnya',
                            ])
                            ->searchable()
                            ->placeholder('Pilih bank')
                            ->columnSpan(1),

                        TextInput::make('bank_reference')
                            ->label('No. Referensi Bank')
                            ->placeholder('Mis. FT26052100012345')
                            ->columnSpan(1),

                        Select::make('status')
                            ->label('Status Pembayaran')
                            ->options(PaymentStatus::class)
                            ->default('Pending')
                            ->required()
                            ->helperText('Pilih "Success" → invoice otomatis ter-update jadi Paid/Partial.')
                            ->columnSpan(1),

                        DateTimePicker::make('reconciled_at')
                            ->label('Tanggal Rekonsiliasi')
                            ->seconds(false)
                            ->native(false)
                            ->disabled()
                            ->dehydrated()
                            ->placeholder('Auto-fill saat status = Success')
                            ->columnSpan(1),
                    ])
                    ->columns(2),
            ]);
    }
}
