<?php

namespace App\Filament\Student\Resources\Logbooks\Schemas;

use App\Enums\Thesis\ThesisTopicStatus;
use App\Models\ThesisTopic;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LogbookForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Catat Bimbingan')
                    ->columnSpanFull()
                    ->columns(2)
                    ->components([
                        Select::make('thesis_topic_id')
                            ->label('Topik Skripsi')
                            ->options(fn () => ThesisTopic::query()
                                ->where('student_id', auth()->user()?->student?->id)
                                ->where('status', ThesisTopicStatus::Approved)
                                ->pluck('title', 'id'))
                            ->required(),
                        DatePicker::make('session_date')
                            ->label('Tanggal Bimbingan')
                            ->required()
                            ->default(now()),
                        TextInput::make('duration_minutes')
                            ->label('Durasi (menit)')
                            ->numeric()
                            ->default(60),
                        Textarea::make('topic_discussed')
                            ->label('Topik yang Dibahas')
                            ->required()
                            ->columnSpanFull(),
                        Textarea::make('progress_summary')
                            ->label('Ringkasan Progres')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
