<?php

namespace App\Filament\Student\Resources\Invoices;

use App\Enums\Finance\InvoiceStatus;
use App\Filament\Student\Resources\Invoices\Pages\ListInvoices;
use App\Filament\Student\Resources\Invoices\Pages\ViewInvoice;
use App\Models\Invoice;
use BackedEnum;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\ViewAction;
use Illuminate\Database\Eloquent\Builder;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static ?string $navigationLabel = 'Tagihan';

    protected static ?string $modelLabel = 'Tagihan';

    protected static ?string $pluralModelLabel = 'Tagihan';

    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        $studentId = auth()->user()?->student?->id;

        return parent::getEloquentQuery()->where('student_id', $studentId);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Tagihan')
                ->columnSpanFull()
                ->columns(2)
                ->schema([
                    TextInput::make('invoice_number')->label('No. Invoice')->disabled()->copyable(),
                    Placeholder::make('semester_name')
                        ->label('Semester')
                        ->content(fn ($record) => $record?->semester?->name ?? '—'),
                    TextInput::make('virtual_account')->label('Virtual Account')->disabled()->copyable(),
                    TextInput::make('bank_code')->label('Bank')->disabled(),
                    TextInput::make('issue_date')->label('Tanggal Terbit')->disabled(),
                    TextInput::make('due_date')->label('Jatuh Tempo')->disabled(),
                    TextInput::make('status')->label('Status')->disabled(),
                ]),

            Section::make('Rincian Pembayaran')
                ->columnSpanFull()
                ->columns(2)
                ->schema([
                    TextInput::make('subtotal')->label('Subtotal')->disabled()->prefix('Rp'),
                    TextInput::make('discount_amount')->label('Diskon')->disabled()->prefix('Rp'),
                    TextInput::make('fine_amount')->label('Denda')->disabled()->prefix('Rp'),
                    TextInput::make('scholarship_amount')->label('Beasiswa')->disabled()->prefix('Rp'),
                    TextInput::make('total_amount')->label('Total Tagihan')->disabled()->prefix('Rp'),
                    TextInput::make('paid_amount')->label('Sudah Dibayar')->disabled()->prefix('Rp'),
                ]),

            Section::make('Catatan')
                ->columnSpanFull()
                ->schema([
                    Textarea::make('notes')->label('Catatan')->disabled()->rows(2),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('issue_date', 'desc')
            ->columns([
                TextColumn::make('invoice_number')
                    ->label('No. Invoice')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('semester.name')
                    ->label('Semester')
                    ->badge(),

                TextColumn::make('total_amount')
                    ->label('Total Tagihan')
                    ->money('IDR')
                    ->alignRight(),

                TextColumn::make('paid_amount')
                    ->label('Sudah Dibayar')
                    ->money('IDR')
                    ->alignRight(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state instanceof InvoiceStatus ? $state->value : $state) {
                        'Paid'      => 'success',
                        'Partial'   => 'warning',
                        'Overdue'   => 'danger',
                        'Cancelled' => 'gray',
                        default     => 'info',
                    }),

                TextColumn::make('due_date')
                    ->label('Jatuh Tempo')
                    ->date('d M Y')
                    ->color(fn ($record) => $record->due_date?->isPast()
                        && ! in_array($record->status?->value, ['Paid', 'Cancelled'], true)
                        ? 'danger' : null),

                TextColumn::make('virtual_account')
                    ->label('Virtual Account')
                    ->copyable()
                    ->placeholder('—'),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInvoices::route('/'),
            'view'  => ViewInvoice::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
