<?php

namespace App\Filament\Student\Resources\ScholarshipRecipients;

use App\Filament\Student\Resources\ScholarshipRecipients\Pages\CreateScholarshipRecipient;
use App\Filament\Student\Resources\ScholarshipRecipients\Pages\ListScholarshipRecipients;
use App\Filament\Student\Resources\ScholarshipRecipients\Pages\ViewScholarshipRecipient;
use App\Filament\Student\Resources\ScholarshipRecipients\Schemas\ScholarshipRecipientForm;
use App\Filament\Student\Resources\ScholarshipRecipients\Tables\ScholarshipRecipientsTable;
use App\Models\ScholarshipRecipient;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ScholarshipRecipientResource extends Resource
{
    protected static ?string $model = ScholarshipRecipient::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static ?string $navigationLabel = 'Beasiswa';

    protected static ?string $modelLabel = 'Pengajuan Beasiswa';

    protected static ?string $pluralModelLabel = 'Beasiswa';

    protected static ?int $navigationSort = 4;

    public static function getEloquentQuery(): Builder
    {
        $studentId = auth()->user()?->student?->id;

        return parent::getEloquentQuery()->where('student_id', $studentId);
    }

    public static function form(Schema $schema): Schema
    {
        return ScholarshipRecipientForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ScholarshipRecipientsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListScholarshipRecipients::route('/'),
            'create' => CreateScholarshipRecipient::route('/create'),
            'view' => ViewScholarshipRecipient::route('/{record}'),
        ];
    }
}
