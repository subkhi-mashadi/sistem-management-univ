<?php

namespace App\Filament\Admin\Resources\Transcripts;

use App\Filament\Admin\Resources\Transcripts\Pages\CreateTranscript;
use App\Filament\Admin\Resources\Transcripts\Pages\EditTranscript;
use App\Filament\Admin\Resources\Transcripts\Pages\ListTranscripts;
use App\Filament\Admin\Resources\Transcripts\Schemas\TranscriptForm;
use App\Filament\Admin\Resources\Transcripts\Tables\TranscriptsTable;
use App\Models\Transcript;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TranscriptResource extends Resource
{
    protected static ?string $model = Transcript::class;

    protected static string | \UnitEnum | null $navigationGroup = 'KRS & Nilai';

    protected static ?string $navigationLabel = 'Transkrip';

    protected static ?string $modelLabel = 'Transkrip';

    protected static ?string $pluralModelLabel = 'Transkrip';

    protected static ?int $navigationSort = 43;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    public static function form(Schema $schema): Schema
    {
        return TranscriptForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TranscriptsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTranscripts::route('/'),
            'create' => CreateTranscript::route('/create'),
            'edit' => EditTranscript::route('/{record}/edit'),
        ];
    }
}
