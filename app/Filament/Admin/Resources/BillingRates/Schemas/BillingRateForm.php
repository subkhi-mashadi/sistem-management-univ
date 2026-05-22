<?php

namespace App\Filament\Admin\Resources\BillingRates\Schemas;

use App\Models\UktGroup;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BillingRateForm
{
    public static function configure(Schema $schema): Schema
    {
        $currentYear = (int) now()->format('Y');
        $years = array_combine(
            range($currentYear + 1, $currentYear - 10),
            range($currentYear + 1, $currentYear - 10),
        );

        return $schema
            ->components([
                Section::make('Skema Tagihan')
                    ->description('Tarif diterapkan ke mahasiswa berdasarkan kombinasi: Prodi × Angkatan × Golongan UKT.')
                    ->columnSpanFull()
                    ->columns(2)->components([
                        Select::make('billing_component_id')
                            ->label('Komponen Tagihan')
                            ->relationship('component', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->helperText('Misal: UKT, SPP, Pengembangan, Praktikum.'),
                        Select::make('study_program_id')
                            ->label('Program Studi')
                            ->relationship('studyProgram', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder('Berlaku semua prodi (biarkan kosong)')
                            ->helperText('Kosongkan jika tarif berlaku untuk SEMUA prodi.'),
                        Select::make('enrollment_year')
                            ->label('Angkatan')
                            ->options($years)
                            ->placeholder('Berlaku semua angkatan (biarkan kosong)')
                            ->searchable()
                            ->helperText('Kosongkan jika tarif berlaku untuk SEMUA angkatan.'),
                        Select::make('ukt_group')
                            ->label('Golongan UKT')
                            ->options(fn () => UktGroup::query()
                                ->where('is_active', true)
                                ->orderBy('code')
                                ->get()
                                ->mapWithKeys(fn (UktGroup $g) => [$g->code => "{$g->code} — {$g->name}"])
                                ->all())
                            ->searchable()
                            ->placeholder('Berlaku semua golongan (biarkan kosong)')
                            ->helperText('Kosongkan jika tarif tidak terkait Golongan UKT (mis. biaya wisuda).'),
                        TextInput::make('amount')
                            ->label('Nominal Tarif')
                            ->prefix('Rp')
                            ->numeric()
                            ->minValue(0)
                            ->required(),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->inline(false),
                        DatePicker::make('effective_from')
                            ->label('Berlaku Dari')
                            ->native(false),
                        DatePicker::make('effective_to')
                            ->label('Berlaku Sampai')
                            ->native(false)
                            ->helperText('Kosongkan jika tidak ada batas akhir.'),
                    ]),
            ]);
    }
}
