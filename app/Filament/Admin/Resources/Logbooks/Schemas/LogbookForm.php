<?php

namespace App\Filament\Admin\Resources\Logbooks\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class LogbookForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('thesis_topic_id')
                    ->label('Topik Skripsi')
                    ->relationship('thesisTopic', 'title')
                    ->required(),
                Select::make('student_id')
                    ->label('Mahasiswa')
                    ->relationship('student', 'id')
                    ->required(),
                Select::make('lecturer_id')
                    ->label('Dosen')
                    ->relationship('lecturer', 'id')
                    ->required(),
                DatePicker::make('session_date')
                    ->label('Tanggal Sesi')
                    ->required(),
                TextInput::make('duration_minutes')
                    ->label('Durasi (Menit)')
                    ->required()
                    ->numeric()
                    ->default(60),
                Textarea::make('topic_discussed')
                    ->label('Topik Dibahas')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('progress_summary')
                    ->label('Ringkasan Progres')
                    ->columnSpanFull(),
                Textarea::make('lecturer_feedback')
                    ->label('Feedback Dosen')
                    ->columnSpanFull(),
                Toggle::make('is_verified')
                    ->label('Terverifikasi')
                    ->required(),
                DateTimePicker::make('verified_at')
                    ->label('Diverifikasi'),
            ]);
    }
}
