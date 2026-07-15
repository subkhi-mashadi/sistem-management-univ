<?php

namespace App\Filament\Admin\Resources\Approvals;

use App\Filament\Admin\Resources\Approvals\Pages\CreateApproval;
use App\Filament\Admin\Resources\Approvals\Pages\EditApproval;
use App\Filament\Admin\Resources\Approvals\Pages\ListApprovals;
use App\Filament\Admin\Resources\Approvals\Schemas\ApprovalForm;
use App\Filament\Admin\Resources\Approvals\Tables\ApprovalsTable;
use App\Models\Approval;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ApprovalResource extends Resource
{
    protected static ?string $model = Approval::class;

    protected static string|\UnitEnum|null $navigationGroup = 'E-Office (Lanjutan)';

    protected static ?string $navigationLabel = 'Persetujuan';

    protected static ?string $modelLabel = 'Persetujuan';

    protected static ?string $pluralModelLabel = 'Persetujuan';

    protected static ?int $navigationSort = 64;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCheckBadge;

    public static function form(Schema $schema): Schema
    {
        return ApprovalForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ApprovalsTable::configure($table);
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
            'index' => ListApprovals::route('/'),
            'create' => CreateApproval::route('/create'),
            'edit' => EditApproval::route('/{record}/edit'),
        ];
    }
}
