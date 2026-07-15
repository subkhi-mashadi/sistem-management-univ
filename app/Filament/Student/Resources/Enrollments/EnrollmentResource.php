<?php

namespace App\Filament\Student\Resources\Enrollments;

use App\Filament\Student\Resources\Enrollments\Pages\CreateEnrollment;
use App\Filament\Student\Resources\Enrollments\Pages\EditEnrollment;
use App\Filament\Student\Resources\Enrollments\Pages\ListEnrollments;
use App\Filament\Student\Resources\Enrollments\RelationManagers\KrsItemsRelationManager;
use App\Filament\Student\Resources\Enrollments\Schemas\EnrollmentForm;
use App\Filament\Student\Resources\Enrollments\Tables\EnrollmentsTable;
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

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static ?string $navigationLabel = 'KRS';

    protected static ?string $modelLabel = 'KRS';

    protected static ?string $pluralModelLabel = 'KRS';

    /**
     * Mahasiswa hanya bisa lihat & ubah KRS miliknya sendiri.
     */
    public static function getEloquentQuery(): Builder
    {
        $studentId = auth()->user()?->student?->id;

        return parent::getEloquentQuery()->where('student_id', $studentId);
    }

    public static function form(Schema $schema): Schema
    {
        return EnrollmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EnrollmentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            KrsItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEnrollments::route('/'),
            'create' => CreateEnrollment::route('/create'),
            'edit' => EditEnrollment::route('/{record}/edit'),
        ];
    }
}
