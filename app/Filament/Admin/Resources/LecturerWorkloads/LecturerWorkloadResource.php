<?php

namespace App\Filament\Admin\Resources\LecturerWorkloads;

use App\Filament\Admin\Resources\LecturerWorkloads\Pages\CreateLecturerWorkload;
use App\Filament\Admin\Resources\LecturerWorkloads\Pages\EditLecturerWorkload;
use App\Filament\Admin\Resources\LecturerWorkloads\Pages\ListLecturerWorkloads;
use App\Filament\Admin\Resources\LecturerWorkloads\Schemas\LecturerWorkloadForm;
use App\Filament\Admin\Resources\LecturerWorkloads\Tables\LecturerWorkloadsTable;
use App\Models\LecturerWorkload;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LecturerWorkloadResource extends Resource
{
    protected static ?string $model = LecturerWorkload::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Penjadwalan';

    protected static ?string $navigationLabel = 'Beban Kerja Dosen';

    protected static ?string $modelLabel = 'Beban Kerja Dosen';

    protected static ?string $pluralModelLabel = 'Beban Kerja Dosen';

    protected static ?int $navigationSort = 33;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedScale;

    public static function form(Schema $schema): Schema
    {
        return LecturerWorkloadForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LecturerWorkloadsTable::configure($table);
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
            'index' => ListLecturerWorkloads::route('/'),
            'create' => CreateLecturerWorkload::route('/create'),
            'edit' => EditLecturerWorkload::route('/{record}/edit'),
        ];
    }
}
