<?php

namespace App\Filament\Student\Resources\Transcripts;

use App\Filament\Student\Resources\Transcripts\Pages\ListTranscripts;
use App\Models\Transcript;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TranscriptResource extends Resource
{
    protected static ?string $model = Transcript::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static ?string $navigationLabel = 'Transkrip Nilai';

    protected static ?string $modelLabel = 'Transkrip';

    protected static ?string $pluralModelLabel = 'Transkrip Nilai';

    protected static ?int $navigationSort = 3;

    public static function getEloquentQuery(): Builder
    {
        $studentId = auth()->user()?->student?->id;

        return parent::getEloquentQuery()->where('student_id', $studentId);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('semester_id', 'desc')
            ->columns([
                TextColumn::make('semester.name')
                    ->label('Semester')
                    ->badge(),

                TextColumn::make('sks_attempted')
                    ->label('SKS Tempuh')
                    ->alignCenter(),

                TextColumn::make('sks_acquired')
                    ->label('SKS Lulus')
                    ->alignCenter(),

                TextColumn::make('semester_gpa')
                    ->label('IPS')
                    ->alignCenter()
                    ->formatStateUsing(fn ($state) => number_format((float) $state, 2)),

                TextColumn::make('cumulative_gpa')
                    ->label('IPK')
                    ->alignCenter()
                    ->formatStateUsing(fn ($state) => number_format((float) $state, 2))
                    ->weight(\Filament\Support\Enums\FontWeight::Bold),

                TextColumn::make('sks_cumulative')
                    ->label('SKS Kumulatif')
                    ->alignCenter(),

                TextColumn::make('academic_status')
                    ->label('Status Akademik')
                    ->badge()
                    ->color(fn ($state) => match ((string) $state) {
                        'Normal'    => 'success',
                        'Warning'   => 'warning',
                        'DO Risk'   => 'danger',
                        default     => 'gray',
                    }),
            ]);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTranscripts::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
