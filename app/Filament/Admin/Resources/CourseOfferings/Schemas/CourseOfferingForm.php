<?php

namespace App\Filament\Admin\Resources\CourseOfferings\Schemas;

use App\Models\Course;
use App\Models\Lecturer;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class CourseOfferingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama')
                    ->columnSpanFull()
                    ->columns(2)->components([
                        Select::make('semester_id')
                            ->label('Semester')
                            ->relationship('semester', 'name', fn ($query) => $query->orderByDesc('is_active'))
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('course_id')
                            ->label('Mata Kuliah')
                            ->options(fn () => Course::query()
                                ->where('is_active', true)
                                ->with('curriculum.studyProgram')
                                ->get()
                                ->mapWithKeys(fn (Course $c) => [
                                    $c->id => $c->code.' — '.$c->name.' ('.($c->curriculum?->studyProgram?->code ?? '-').')',
                                ])
                                ->all())
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Set $set) {
                                $set('lecturer_ids', null);
                            }),
                        TextInput::make('class_code')
                            ->label('Kelas')
                            ->placeholder('A, B, C, …')
                            ->required(),
                        TextInput::make('quota')
                            ->label('Kuota')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->default(40),
                        Select::make('lecturer_ids')
                            ->label('Dosen Pengampu')
                            ->multiple()
                            ->options(function (Get $get) {
                                $courseId = $get('course_id');
                                if (! $courseId) {
                                    return [];
                                }

                                $course = Course::with('curriculum')->find($courseId);
                                $prodiId = $course?->curriculum?->study_program_id;
                                if (! $prodiId) {
                                    return [];
                                }

                                return Lecturer::query()
                                    ->where('study_program_id', $prodiId)
                                    ->where('is_active', true)
                                    ->with('user:id,full_name,name')
                                    ->get()
                                    ->mapWithKeys(fn (Lecturer $l) => [
                                        $l->id => ($l->user?->full_name ?? $l->user?->name).' — NIDN '.($l->nidn ?? '-'),
                                    ])
                                    ->all();
                            })
                            ->searchable()
                            ->preload()
                            ->columnSpanFull()
                            ->disabled(fn (Get $get) => ! $get('course_id')),
                        TextInput::make('enrolled_count')
                            ->label('Terdaftar')
                            ->numeric()
                            ->default(0)
                            ->disabled()
                            ->dehydrated()
                            ->helperText('Otomatis dihitung dari jumlah KRS aktif (via KrsItemObserver).'),
                        Toggle::make('is_open')
                            ->label('Dibuka untuk KRS')
                            ->default(true)
                            ->inline(false),
                    ]),
            ]);
    }
}
