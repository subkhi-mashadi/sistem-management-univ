<?php

namespace App\Filament\Lecturer\Resources\Enrollments;

use App\Filament\Lecturer\Resources\Enrollments\Pages\ListEnrollments;
use App\Filament\Lecturer\Resources\Enrollments\Pages\ViewEnrollment;
use App\Filament\Lecturer\Resources\Enrollments\Schemas\EnrollmentView;
use App\Filament\Lecturer\Resources\Enrollments\Tables\EnrollmentsTable;
use App\Models\Enrollment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EnrollmentResource extends Resource
{
    protected static ?string $model = Enrollment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static ?string $navigationLabel = 'Approval KRS';

    protected static ?string $modelLabel = 'KRS Mahasiswa';

    protected static ?string $pluralModelLabel = 'Approval KRS';

    /**
     * Dosen hanya lihat KRS dari mahasiswa walinya.
     */
    public static function getEloquentQuery(): Builder
    {
        $lecturerId = auth()->user()?->lecturer?->id;

        return parent::getEloquentQuery()
            ->whereHas('student', fn (Builder $q) => $q->where('academic_advisor_id', $lecturerId));
    }

    public static function form(Schema $schema): Schema
    {
        return EnrollmentView::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EnrollmentsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEnrollments::route('/'),
            'view'  => ViewEnrollment::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
