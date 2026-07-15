<?php

namespace App\Filament\Lecturer\Resources\ThesisAdvisees;

use App\Enums\Thesis\ThesisAdvisorStatus;
use App\Filament\Lecturer\Resources\ThesisAdvisees\Pages\ListThesisAdvisees;
use App\Filament\Lecturer\Resources\ThesisAdvisees\Pages\ViewThesisAdvisee;
use App\Filament\Lecturer\Resources\ThesisAdvisees\Tables\ThesisAdviseesTable;
use App\Models\ThesisTopic;
use BackedEnum;
use Filament\Forms\Components\Placeholder;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ThesisAdviseeResource extends Resource
{
    protected static ?string $model = ThesisTopic::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static ?string $navigationLabel = 'Bimbingan Skripsi';

    protected static ?string $modelLabel = 'Mahasiswa Bimbingan';

    protected static ?string $pluralModelLabel = 'Bimbingan Skripsi';

    protected static ?int $navigationSort = 4;

    public static function getEloquentQuery(): Builder
    {
        $lecturerId = auth()->user()?->lecturer?->id;

        return parent::getEloquentQuery()
            ->whereHas('advisors', fn (Builder $q) => $q
                ->where('lecturer_id', $lecturerId)
                ->where('status', ThesisAdvisorStatus::Active));
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Detail Topik')
                ->columnSpanFull()
                ->columns(2)
                ->components([
                    Placeholder::make('student_display')
                        ->label('Mahasiswa')
                        ->content(fn (?ThesisTopic $record) => $record?->student?->user?->name ?? '—'),
                    Placeholder::make('nim_display')
                        ->label('NIM')
                        ->content(fn (?ThesisTopic $record) => $record?->student?->nim ?? '—'),
                    Placeholder::make('title_display')
                        ->label('Judul')
                        ->columnSpanFull()
                        ->content(fn (?ThesisTopic $record) => $record?->title ?? '—'),
                    Placeholder::make('abstract_display')
                        ->label('Abstrak')
                        ->columnSpanFull()
                        ->content(fn (?ThesisTopic $record) => $record?->abstract ?? '—'),
                    Placeholder::make('status_display')
                        ->label('Status')
                        ->content(fn (?ThesisTopic $record) => $record?->status?->value ?? '—'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return ThesisAdviseesTable::configure($table);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListThesisAdvisees::route('/'),
            'view' => ViewThesisAdvisee::route('/{record}'),
        ];
    }
}
