<?php

namespace App\Filament\Student\Resources\Enrollments\Schemas;

use App\Models\Semester;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class EnrollmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Periode')
                    ->description('Pilih semester & isi catatan. Setelah klik "Buat", Anda diarahkan ke halaman pemilihan Mata Kuliah.')
                    ->columnSpanFull()
                    ->schema([
                        Placeholder::make('info')
                            ->hiddenLabel()
                            ->content(new HtmlString(
                                '<div class="rounded-lg border border-warning-300 bg-warning-50 dark:bg-warning-950/50 p-3 text-sm text-warning-800 dark:text-warning-200">'
                                .'<strong>📌 Alur:</strong> 1) Pilih semester &amp; klik <strong>Buat</strong> → 2) Tambah Mata Kuliah satu per satu → 3) Klik <strong>Submit ke Dosen Wali</strong>.'
                                .'</div>'
                            ))
                            ->visibleOn('create')
                            ->columnSpanFull(),

                        Select::make('semester_id')
                            ->label('Semester')
                            ->options(fn () => Semester::query()
                                ->where('is_active', true)
                                ->pluck('name', 'id')
                                ->all())
                            ->default(fn () => Semester::where('is_active', true)->value('id'))
                            ->required()
                            ->helperText('Hanya semester aktif yang muncul.')
                            ->columnSpan(1),

                        TextInput::make('max_sks')
                            ->label('Maks. SKS')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(30)
                            ->default(24)
                            ->required()
                            ->disabled()
                            ->dehydrated()
                            ->helperText('Default 24 SKS. Hubungi Dosen Wali jika perlu lebih.')
                            ->columnSpan(1),

                        TextInput::make('total_sks_taken')
                            ->label('Total SKS Diambil')
                            ->numeric()
                            ->default(0)
                            ->disabled()
                            ->dehydrated()
                            ->helperText('Otomatis dihitung dari MK yang dipilih.')
                            ->columnSpan(1),

                        TextInput::make('status')
                            ->label('Status')
                            ->default('Draft')
                            ->disabled()
                            ->dehydrated()
                            ->columnSpan(1),

                        Textarea::make('notes')
                            ->label('Catatan untuk Dosen Wali (opsional)')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
