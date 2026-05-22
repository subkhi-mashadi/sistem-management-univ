<?php

namespace App\Filament\Admin\Resources\Scholarships\Schemas;

use App\Enums\Finance\CoverageType;
use App\Enums\Finance\ScholarshipType;
use App\Models\BillingComponent;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class ScholarshipForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Beasiswa')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Program Beasiswa')
                            ->placeholder('Mis. Beasiswa Prestasi Akademik 2026')
                            ->required()
                            ->columnSpanFull(),

                        Select::make('type')
                            ->label('Tipe / Kategori')
                            ->options(ScholarshipType::class)
                            ->required()
                            ->helperText('Pengelompokan untuk laporan.')
                            ->columnSpan(1),

                        TextInput::make('code')
                            ->label('Kode')
                            ->placeholder('Auto-generate')
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpan(1),

                        Textarea::make('description')
                            ->label('Deskripsi / Persyaratan')
                            ->placeholder('Mis. IPK ≥ 3.5, dari keluarga kurang mampu')
                            ->rows(2)
                            ->columnSpanFull(),

                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->inline(false)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Cakupan Tagihan')
                    ->description('Atur komponen tagihan mana yang dicover & seberapa besar coverage-nya.')
                    ->columnSpanFull()
                    ->schema([
                        Select::make('covered_components')
                            ->label('Komponen Ditanggung')
                            ->multiple()
                            ->options(fn () => BillingComponent::query()
                                ->where('is_active', true)
                                ->get()
                                ->mapWithKeys(fn (BillingComponent $c) => [$c->code => "{$c->code} — {$c->name}"])
                                ->all())
                            ->searchable()
                            ->preload()
                            ->required()
                            ->helperText('Bisa lebih dari satu (mis. UKT + SPP).')
                            ->columnSpanFull(),

                        Select::make('coverage_type')
                            ->label('Skema Coverage')
                            ->options([
                                CoverageType::Full->value => 'Full — Tanggung seluruh nominal komponen',
                                CoverageType::PartialPercent->value => 'Partial Percent — Persentase dari nominal',
                                CoverageType::PartialAmount->value => 'Partial Amount — Nominal tetap (Rp)',
                            ])
                            ->default(CoverageType::Full->value)
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Set $set, $state) {
                                if ($state === CoverageType::Full->value) {
                                    $set('coverage_value', 100);
                                } else {
                                    $set('coverage_value', 0);
                                }
                            })
                            ->columnSpan(1),

                        TextInput::make('coverage_value')
                            ->label(fn (Get $get) => match ($get('coverage_type')) {
                                CoverageType::PartialPercent->value => 'Persentase Coverage',
                                CoverageType::PartialAmount->value => 'Nominal Coverage',
                                default => 'Nilai Coverage',
                            })
                            ->prefix(fn (Get $get) => $get('coverage_type') === CoverageType::PartialAmount->value ? 'Rp' : null)
                            ->suffix(fn (Get $get) => $get('coverage_type') === CoverageType::PartialPercent->value ? '%' : null)
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->required()
                            ->disabled(fn (Get $get) => $get('coverage_type') === CoverageType::Full->value)
                            ->helperText(fn (Get $get) => match ($get('coverage_type')) {
                                CoverageType::Full->value => 'Otomatis 100% — tidak perlu diisi.',
                                CoverageType::PartialPercent->value => 'Contoh: 50 → tanggung setengah komponen.',
                                CoverageType::PartialAmount->value => 'Contoh: 2.000.000 → tanggung Rp 2 jt fix.',
                                default => null,
                            })
                            ->columnSpan(1),
                    ])
                    ->columns(2),
            ]);
    }
}
