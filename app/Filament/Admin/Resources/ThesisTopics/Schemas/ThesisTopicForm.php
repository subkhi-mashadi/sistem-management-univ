<?php

namespace App\Filament\Admin\Resources\ThesisTopics\Schemas;

use App\Models\ThesisTopic;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ThesisTopicForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama')
                    ->columnSpanFull()
                    ->columns(2)->components([
                        Select::make('student_id')
                            ->label('Mahasiswa')
                            ->relationship('student', 'nim')->searchable()->preload()
                            ->required()
                            ->disabled(fn (?ThesisTopic $record) => $record !== null),
                        TextInput::make('title')
                            ->label('Judul')
                            ->required()
                            ->disabled(fn (?ThesisTopic $record) => $record !== null),
                        TextInput::make('title_en')
                            ->label('Judul (EN)')
                            ->disabled(fn (?ThesisTopic $record) => $record !== null),
                        Textarea::make('abstract')
                            ->label('Abstrak')
                            ->columnSpanFull()
                            ->rows(8)
                            ->disabled(fn (?ThesisTopic $record) => $record !== null),
                        TextInput::make('keywords')
                            ->label('Kata Kunci')
                            ->disabled(fn (?ThesisTopic $record) => $record !== null),
                        TextInput::make('research_field')
                            ->label('Bidang Penelitian')
                            ->disabled(fn (?ThesisTopic $record) => $record !== null),
                    ]),

                Section::make('Status Review')
                    ->columnSpanFull()
                    ->columns(2)
                    ->visible(fn (?ThesisTopic $record) => $record !== null)
                    ->components([
                        Placeholder::make('status_display')
                            ->label('Status')
                            ->content(fn (?ThesisTopic $record) => $record?->status?->value ?? '—'),
                        Placeholder::make('approver_display')
                            ->label('Disetujui Oleh')
                            ->content(fn (?ThesisTopic $record) => $record?->approver?->name ?? '—'),
                        Placeholder::make('approved_at_display')
                            ->label('Tanggal Disetujui')
                            ->content(fn (?ThesisTopic $record) => $record?->approved_at?->format('d M Y H:i') ?? '—'),
                        Textarea::make('review_notes')
                            ->label('Catatan Review')
                            ->helperText('Silahkan tulis catatan review jika menolak topik skripsi ini.')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
