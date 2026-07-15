<?php

namespace App\Filament\Admin\Resources\Enrollments\RelationManagers;

use App\Enums\Krs\KrsItemStatus;
use App\Models\CourseOffering;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KrsItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $title = 'Rincian Mata Kuliah KRS';

    public function form(Schema $schema): Schema
    {
        $enrollment = $this->getOwnerRecord();
        $semesterId = $enrollment->semester_id;
        $alreadyTakenIds = $enrollment->items()->pluck('course_offering_id')->all();

        return $schema
            ->columns(2)
            ->components([
                Select::make('course_offering_id')
                    ->label('Penawaran MK')
                    ->options(fn () => CourseOffering::query()
                        ->where('semester_id', $semesterId)
                        ->whereNotIn('id', $alreadyTakenIds)
                        ->with('course')
                        ->get()
                        ->mapWithKeys(fn (CourseOffering $o) => [
                            $o->id => ($o->course?->code ?? '?')
                                .' — '.($o->course?->name ?? 'MK')
                                .' (Kelas '.$o->class_code.', '.($o->course?->total_sks ?? 0).' SKS)',
                        ])
                        ->all())
                    ->getOptionLabelUsing(function ($value): ?string {
                        $o = CourseOffering::with('course')->find($value);
                        if (! $o) {
                            return null;
                        }

                        return ($o->course?->code ?? '?').' — '.($o->course?->name ?? 'MK').' (Kelas '.$o->class_code.')';
                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    ->columnSpanFull(),

                Select::make('status')
                    ->label('Status')
                    ->options(KrsItemStatus::class)
                    ->default('Active')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('courseOffering.course.code')
                    ->label('Kode MK')
                    ->badge(),
                TextColumn::make('courseOffering.course.name')
                    ->label('Mata Kuliah')
                    ->wrap(),
                TextColumn::make('courseOffering.class_code')
                    ->label('Kelas')
                    ->badge(),
                TextColumn::make('courseOffering.course.total_sks')
                    ->label('SKS')
                    ->alignCenter(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah MK')
                    ->icon('heroicon-o-plus'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
