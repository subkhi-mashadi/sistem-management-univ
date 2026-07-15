<?php

namespace App\Filament\Student\Resources\ThesisTopics;

use App\Enums\Thesis\ThesisTopicStatus;
use App\Filament\Student\Resources\ThesisTopics\Pages\CreateThesisTopic;
use App\Filament\Student\Resources\ThesisTopics\Pages\EditThesisTopic;
use App\Filament\Student\Resources\ThesisTopics\Pages\ListThesisTopics;
use App\Filament\Student\Resources\ThesisTopics\Pages\ViewThesisTopic;
use App\Filament\Student\Resources\ThesisTopics\Schemas\ThesisTopicForm;
use App\Filament\Student\Resources\ThesisTopics\Tables\ThesisTopicsTable;
use App\Models\ThesisTopic;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ThesisTopicResource extends Resource
{
    protected static ?string $model = ThesisTopic::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static ?string $navigationLabel = 'Topik Skripsi';

    protected static ?string $modelLabel = 'Topik Skripsi';

    protected static ?string $pluralModelLabel = 'Topik Skripsi';

    protected static ?int $navigationSort = 5;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('student_id', auth()->user()?->student?->id);
    }

    public static function form(Schema $schema): Schema
    {
        return ThesisTopicForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ThesisTopicsTable::configure($table);
    }

    public static function canEdit(Model $record): bool
    {
        return in_array($record->status, [
            ThesisTopicStatus::Draft,
            ThesisTopicStatus::Rejected,
            ThesisTopicStatus::Revision,
        ], true);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListThesisTopics::route('/'),
            'create' => CreateThesisTopic::route('/create'),
            'edit' => EditThesisTopic::route('/{record}/edit'),
            'view' => ViewThesisTopic::route('/{record}'),
        ];
    }
}
