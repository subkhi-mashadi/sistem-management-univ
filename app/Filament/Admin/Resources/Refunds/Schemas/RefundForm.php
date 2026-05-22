<?php

namespace App\Filament\Admin\Resources\Refunds\Schemas;

use App\Enums\Finance\RefundReason;
use App\Enums\Finance\RefundStatus;
use App\Models\Payment;
use App\Models\Student;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class RefundForm
{
    private static function paymentLabel(Payment $p): string
    {
        $mhs = $p->student?->user?->full_name ?? $p->student?->user?->name ?? 'Mhs';

        return $p->payment_number.' — '.$mhs.' (Rp '.number_format((float) $p->amount, 0, ',', '.').')';
    }

    private static function studentLabel(Student $s): string
    {
        $name = $s->user?->full_name ?? $s->user?->name ?? 'Mhs';

        return $s->nim.' — '.$name;
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Refund')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('refund_number')
                            ->label('Nomor Refund')
                            ->placeholder('Auto: REF-YYYYMM-NNNN')
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpan(1),

                        TextInput::make('amount')
                            ->label('Nominal Refund')
                            ->prefix('Rp')
                            ->numeric()
                            ->minValue(1)
                            ->required()
                            ->columnSpan(1),

                        Select::make('payment_id')
                            ->label('Pembayaran Sumber')
                            ->options(fn () => Payment::query()
                                ->with('student.user:id,full_name,name')
                                ->where('status', 'Success')
                                ->orderByDesc('created_at')
                                ->limit(100)
                                ->get()
                                ->mapWithKeys(fn (Payment $p) => [$p->id => self::paymentLabel($p)])
                                ->all())
                            ->getOptionLabelUsing(function ($value): ?string {
                                $p = Payment::with('student.user:id,full_name,name')->find($value);

                                return $p ? self::paymentLabel($p) : null;
                            })
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function (Set $set, $state) {
                                if (! $state) {
                                    return;
                                }
                                $payment = Payment::find($state);
                                if ($payment) {
                                    $set('student_id', $payment->student_id);
                                }
                            })
                            ->columnSpan(1),

                        Select::make('student_id')
                            ->label('Mahasiswa')
                            ->options(fn () => Student::query()
                                ->with('user:id,full_name,name')
                                ->get()
                                ->mapWithKeys(fn (Student $s) => [$s->id => self::studentLabel($s)])
                                ->all())
                            ->getOptionLabelUsing(function ($value): ?string {
                                $s = Student::with('user:id,full_name,name')->find($value);

                                return $s ? self::studentLabel($s) : null;
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->disabled()
                            ->dehydrated()
                            ->helperText('Auto-fill dari pembayaran yang dipilih.')
                            ->columnSpan(1),

                        Select::make('reason')
                            ->label('Alasan Refund')
                            ->options(RefundReason::class)
                            ->required()
                            ->columnSpan(1),

                        Select::make('status')
                            ->label('Status')
                            ->options(RefundStatus::class)
                            ->default('Requested')
                            ->required()
                            ->helperText('Tanggal approve & cair otomatis terisi saat status berubah.')
                            ->columnSpan(1),

                        TextInput::make('bank_account')
                            ->label('No. Rekening Tujuan')
                            ->placeholder('Mis. 0123456789 a.n. Nama Mhs')
                            ->columnSpanFull(),

                        Textarea::make('notes')
                            ->label('Catatan')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
