<?php

namespace App\Filament\Student\Resources\ThesisTopics\Schemas;

use App\Models\ThesisTopic;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TagsInput;
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
                Section::make('Status Pengajuan')
                    ->columnSpanFull()
                    ->columns(2)
                    ->visible(fn (?ThesisTopic $record) => $record !== null)
                    ->components([
                        Placeholder::make('status_display')
                            ->label('Status')
                            ->content(fn (?ThesisTopic $record) => $record?->status?->value ?? '—'),
                        Placeholder::make('review_notes_display')
                            ->label('Catatan Review')
                            ->content(fn (?ThesisTopic $record) => $record?->review_notes ?? '—'),
                    ]),

                Section::make('Topik Skripsi')
                    ->columnSpanFull()
                    ->columns(2)
                    ->components([
                        TextInput::make('title')
                            ->label('Judul')
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('title_en')
                            ->label('Judul (Bahasa Inggris)')
                            ->columnSpanFull(),
                        Textarea::make('abstract')
                            ->label('Abstrak')
                            ->rows(8)
                            ->columnSpanFull(),
                        TagsInput::make('keywords')
                            ->label('Kata Kunci')
                            ->columnSpanFull(),
                        TextInput::make('research_field')
                            ->label('Bidang Penelitian'),
                    ]),
            ]);
    }
}
