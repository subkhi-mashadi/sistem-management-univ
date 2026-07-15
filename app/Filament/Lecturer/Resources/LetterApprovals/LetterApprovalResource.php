<?php

namespace App\Filament\Lecturer\Resources\LetterApprovals;

use App\Enums\EOffice\LetterRequestStatus;
use App\Filament\Lecturer\Resources\LetterApprovals\Pages\ListLetterApprovals;
use App\Filament\Lecturer\Resources\LetterApprovals\Pages\ViewLetterApproval;
use App\Filament\Lecturer\Resources\LetterApprovals\Tables\LetterApprovalsTable;
use App\Models\LetterRequest;
use BackedEnum;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LetterApprovalResource extends Resource
{
    protected static ?string $model = LetterRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCheckBadge;

    protected static ?string $navigationLabel = 'Approval Surat';

    protected static ?string $modelLabel = 'Pengajuan Surat';

    protected static ?string $pluralModelLabel = 'Approval Surat';

    protected static ?int $navigationSort = 5;

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();
        $roleIds = $user?->roles->pluck('id') ?? collect();

        return parent::getEloquentQuery()
            ->where('status', LetterRequestStatus::InProgress)
            ->whereHas('currentStep', function (Builder $q) use ($user, $roleIds) {
                $q->where('approver_user_id', $user?->id)
                    ->orWhereIn('approver_role_id', $roleIds);
            });
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Detail Pengajuan')
                ->columnSpanFull()
                ->columns(2)
                ->components([
                    Placeholder::make('letter_number_display')
                        ->label('Nomor Surat')
                        ->content(fn ($record) => $record?->letter_number ?? '—'),
                    Placeholder::make('template_display')
                        ->label('Jenis Surat')
                        ->content(fn ($record) => $record?->template?->name ?? '—'),
                    Placeholder::make('student_display')
                        ->label('Mahasiswa')
                        ->content(fn ($record) => $record?->student?->user?->name ?? '—'),
                    Placeholder::make('step_display')
                        ->label('Step Saat Ini')
                        ->content(fn ($record) => $record?->currentStep?->name ?? '—'),
                    KeyValue::make('form_data')
                        ->label('Data Surat')
                        ->disabled()
                        ->columnSpanFull(),
                    Textarea::make('notes')
                        ->label('Catatan')
                        ->disabled()
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return LetterApprovalsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLetterApprovals::route('/'),
            'view' => ViewLetterApproval::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
