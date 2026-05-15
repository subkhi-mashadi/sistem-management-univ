<?php

namespace App\Filament\Admin\Resources\MbkmEnrollments;

use App\Filament\Admin\Resources\MbkmEnrollments\Pages\CreateMbkmEnrollment;
use App\Filament\Admin\Resources\MbkmEnrollments\Pages\EditMbkmEnrollment;
use App\Filament\Admin\Resources\MbkmEnrollments\Pages\ListMbkmEnrollments;
use App\Filament\Admin\Resources\MbkmEnrollments\Schemas\MbkmEnrollmentForm;
use App\Filament\Admin\Resources\MbkmEnrollments\Tables\MbkmEnrollmentsTable;
use App\Models\MbkmEnrollment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MbkmEnrollmentResource extends Resource
{
    protected static ?string $model = MbkmEnrollment::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Tugas Akhir & MBKM';

    protected static ?string $navigationLabel = 'Pendaftaran MBKM';

    protected static ?string $modelLabel = 'Pendaftaran MBKM';

    protected static ?string $pluralModelLabel = 'Pendaftaran MBKM';

    protected static ?int $navigationSort = 75;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocument;

    public static function form(Schema $schema): Schema
    {
        return MbkmEnrollmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MbkmEnrollmentsTable::configure($table);
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
            'index' => ListMbkmEnrollments::route('/'),
            'create' => CreateMbkmEnrollment::route('/create'),
            'edit' => EditMbkmEnrollment::route('/{record}/edit'),
        ];
    }
}
