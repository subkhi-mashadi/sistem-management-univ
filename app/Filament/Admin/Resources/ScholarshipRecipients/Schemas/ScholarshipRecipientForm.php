<?php

namespace App\Filament\Admin\Resources\ScholarshipRecipients\Schemas;

use App\Enums\Finance\ScholarshipRecipientStatus;
use App\Models\Student;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ScholarshipRecipientForm
{
    private static function studentLabel(Student $s): string
    {
        $name = $s->user?->full_name ?? $s->user?->name ?? 'Mhs';

        return $s->nim.' — '.$name;
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama')
                    ->columnSpanFull()
                    ->schema([
                        Select::make('scholarship_id')
                            ->label('Beasiswa')
                            ->relationship('scholarship', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(1),

                        Select::make('student_id')
                            ->label('Mahasiswa')
                            ->options(fn () => Student::query()
                                ->with('user:id,full_name,name')
                                ->whereHas('user', fn ($q) => $q->where('is_active', true))
                                ->get()
                                ->mapWithKeys(fn (Student $s) => [$s->id => self::studentLabel($s)])
                                ->all())
                            ->getOptionLabelUsing(function ($value): ?string {
                                $s = Student::with('user:id,full_name,name')->find($value);

                                return $s ? self::studentLabel($s) : null;
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(1),

                        Select::make('semester_id')
                            ->label('Semester')
                            ->relationship('semester', 'name')
                            ->searchable()
                            ->preload()
                            ->columnSpan(1),

                        Select::make('status')
                            ->label('Status')
                            ->options(ScholarshipRecipientStatus::class)
                            ->default('Active')
                            ->required()
                            ->columnSpan(1),

                        DatePicker::make('start_date')
                            ->label('Tanggal Mulai')
                            ->default(now())
                            ->native(false)
                            ->required()
                            ->columnSpan(1),

                        DatePicker::make('end_date')
                            ->label('Tanggal Selesai')
                            ->native(false)
                            ->columnSpan(1),

                        TextInput::make('granted_amount')
                            ->label('Nominal Diberikan')
                            ->prefix('Rp')
                            ->numeric()
                            ->minValue(0)
                            ->columnSpanFull(),

                        Textarea::make('notes')
                            ->label('Catatan')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
