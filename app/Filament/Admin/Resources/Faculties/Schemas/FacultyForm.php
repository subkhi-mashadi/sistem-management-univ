<?php

namespace App\Filament\Admin\Resources\Faculties\Schemas;

use App\Enums\Academic\StructuralPosition;
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
                            ->options(function () {
                                return Lecturer::query()
                                    ->where('is_active', true)
                                    ->where('structural_position', StructuralPosition::Dekan->value)
                                    ->with('user:id,full_name,name')
                                    ->get()
                                    ->mapWithKeys(fn (Lecturer $l) => [
                                        $l->user_id => $l->user?->full_name ?? $l->user?->name ?? "Dosen #{$l->id}",
                                    ])
                                    ->all();
                            })
                            ->searchable()
                            ->preload()
                            ->helperText('Hanya menampilkan dosen dengan jabatan struktural "Dekan". Atur di menu Dosen.'),
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
