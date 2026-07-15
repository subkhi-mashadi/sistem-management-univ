<?php

namespace App\Filament\Lecturer\Resources\Enrollments\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EnrollmentView
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas KRS')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Placeholder::make('student_name')
                            ->label('Mahasiswa')
                            ->content(fn ($record) => $record->student?->user?->full_name
                                .' ('.$record->student?->nim.')'),

                        Placeholder::make('semester_name')
                            ->label('Semester')
                            ->content(fn ($record) => $record->semester?->name),

                        TextInput::make('max_sks')
                            ->label('Maks. SKS')
                            ->disabled(),

                        TextInput::make('total_sks_taken')
                            ->label('Total SKS Diambil')
                            ->disabled(),

                        TextInput::make('status')
                            ->label('Status')
                            ->disabled(),

                        Placeholder::make('approved_at')
                            ->label('Disetujui')
                            ->content(fn ($record) => $record->approved_at?->format('d M Y H:i') ?? '—'),
                    ]),

                Section::make('Catatan')
                    ->columnSpanFull()
                    ->schema([
                        Textarea::make('notes')
                            ->label('Catatan')
                            ->disabled()
                            ->rows(2),
                    ]),
            ]);
    }
}
