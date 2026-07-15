<?php

namespace App\Filament\Lecturer\Resources\Logbooks;

use App\Filament\Lecturer\Resources\Logbooks\Pages\ListLogbooks;
use App\Filament\Lecturer\Resources\Logbooks\Pages\ViewLogbook;
use App\Filament\Lecturer\Resources\Logbooks\Tables\LogbooksTable;
use App\Models\Logbook;
use BackedEnum;
use Filament\Forms\Components\Placeholder;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LogbookResource extends Resource
{
    protected static ?string $model = Logbook::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $navigationLabel = 'Logbook Bimbingan';

    protected static ?string $modelLabel = 'Logbook Bimbingan';

    protected static ?string $pluralModelLabel = 'Logbook Bimbingan';

    protected static ?int $navigationSort = 5;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('lecturer_id', auth()->user()?->lecturer?->id);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Detail Bimbingan')
                ->columnSpanFull()
                ->columns(2)
                ->components([
                    Placeholder::make('student_display')
                        ->label('Mahasiswa')
                        ->content(fn (?Logbook $record) => $record?->student?->user?->name ?? '—'),
                    Placeholder::make('topic_display')
                        ->label('Topik')
                        ->content(fn (?Logbook $record) => $record?->thesisTopic?->title ?? '—'),
                    Placeholder::make('discussed_display')
                        ->label('Topik Dibahas')
                        ->columnSpanFull()
                        ->content(fn (?Logbook $record) => $record?->topic_discussed ?? '—'),
                    Placeholder::make('progress_display')
                        ->label('Ringkasan Progres')
                        ->columnSpanFull()
                        ->content(fn (?Logbook $record) => $record?->progress_summary ?? '—'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return LogbooksTable::configure($table);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLogbooks::route('/'),
            'view' => ViewLogbook::route('/{record}'),
        ];
    }
}
