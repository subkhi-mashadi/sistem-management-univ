<?php

namespace App\Filament\Admin\Resources\LetterTemplates\Schemas;

use App\Enums\EOffice\LetterCategory;
use App\Support\EOffice\LetterSystemFields;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LetterTemplateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama')
                    ->columnSpanFull()
                    ->columns(2)->components([
                        TextInput::make('code')
                            ->label('Kode')
                            ->placeholder('Auto-generate')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('name')
                            ->label('Nama')
                            ->required(),
                        Select::make('category')
                            ->label('Kategori')
                            ->options(LetterCategory::class)
                            ->required(),
                        Toggle::make('requires_signature')
                            ->label('Butuh Tanda Tangan')
                            ->required(),
                        Select::make('default_workflow_id')
                            ->label('Alur Persetujuan')
                            ->relationship('defaultWorkflow', 'name')
                            ->helperText('Pilih alur yang sudah dikonfigurasi. Untuk membuat alur baru, buka menu Workflow (Lanjutan).')
                            ->columnSpanFull(),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->required(),
                    ]),

                Section::make('Isi Surat')
                    ->columnSpanFull()
                    ->columns(1)
                    ->components([
                        Textarea::make('body')
                            ->label('Teks Pembuka')
                            ->helperText('Muncul sebelum tabel data mahasiswa di surat. Tidak perlu placeholder — data mahasiswa otomatis ditampilkan terpisah di bawah.')
                            ->rows(4)
                            ->required(),
                        Textarea::make('closing_text')
                            ->label('Teks Penutup')
                            ->helperText('Muncul setelah tabel data mahasiswa, sebelum paragraf penutup baku.')
                            ->rows(4),
                    ]),

                Section::make('Data Surat')
                    ->columnSpanFull()
                    ->columns(1)
                    ->components([
                        CheckboxList::make('data_fields')
                            ->label('Data Otomatis yang Ditampilkan')
                            ->options(LetterSystemFields::options())
                            ->helperText('Diambil otomatis dari data mahasiswa saat surat diajukan — tidak perlu diketik manual, jadi tidak rawan salah.')
                            ->columns(3),
                        Repeater::make('custom_fields')
                            ->label('Field Tambahan (diisi manual oleh mahasiswa)')
                            ->helperText('Untuk data yang memang tidak bisa otomatis, misal alasan cuti atau tujuan surat rekomendasi.')
                            ->schema([
                                TextInput::make('key')
                                    ->label('Key')
                                    ->helperText('Huruf kecil, tanpa spasi. Misal: alasan')
                                    ->required(),
                                TextInput::make('label')
                                    ->label('Label')
                                    ->helperText('Misal: Alasan Cuti')
                                    ->required(),
                            ])
                            ->columns(2)
                            ->addActionLabel('+ Tambah Field')
                            ->defaultItems(0),
                    ]),
            ]);
    }
}
