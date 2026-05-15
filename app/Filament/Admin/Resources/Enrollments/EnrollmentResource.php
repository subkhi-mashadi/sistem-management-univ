<?php

namespace App\Filament\Admin\Resources\Enrollments;

use App\Filament\Admin\Resources\Enrollments\Pages\CreateEnrollment;
use App\Filament\Admin\Resources\Enrollments\Pages\EditEnrollment;
use App\Filament\Admin\Resources\Enrollments\Pages\ListEnrollments;
use App\Filament\Admin\Resources\Enrollments\Schemas\EnrollmentForm;
use App\Filament\Admin\Resources\Enrollments\Tables\EnrollmentsTable;
use App\Models\Enrollment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EnrollmentResource extends Resource
{
    protected static ?string $model = Enrollment::class;

    protected static string|\UnitEnum|null $navigationGroup = 'KRS & Nilai';

    protected static ?string $navigationLabel = 'KRS';

    protected static ?string $modelLabel = 'KRS';

    protected static ?string $pluralModelLabel = 'KRS';

    protected static ?int $navigationSort = 40;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

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
            //
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

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
