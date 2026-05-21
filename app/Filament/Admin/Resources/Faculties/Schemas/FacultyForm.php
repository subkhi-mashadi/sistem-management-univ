<?php

namespace App\Filament\Admin\Resources\Faculties\Schemas;

use App\Models\Faculty;
use App\Models\Lecturer;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FacultyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Fakultas')
                    ->columns(2)
                    ->columnSpanFull()
                    ->components([
                        TextInput::make('code')
                            ->label('Kode')
                            ->placeholder('Auto-generate')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('name')
                            ->label('Nama Fakultas')
                            ->required(),
                        TextInput::make('pddikti_code')
                            ->label('Kode PDDikti'),
                        Select::make('dean_id')
                            ->label('Dekan')
                            ->options(function (?Faculty $record) {
                                if (! $record) {
                                    return [];
                                }

                                return Lecturer::query()
                                    ->where('is_active', true)
                                    ->whereHas('studyProgram', fn ($q) => $q->where('faculty_id', $record->id))
                                    ->with('user:id,full_name,name')
                                    ->get()
                                    ->mapWithKeys(fn (Lecturer $l) => [
                                        $l->user_id => ($l->user?->full_name ?? $l->user?->name)
                                    ])
                                    ->all();
                            })
                            ->searchable()
                            ->preload(),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->columnSpanFull(),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->inline(false),
                    ]),
            ]);
    }
}
