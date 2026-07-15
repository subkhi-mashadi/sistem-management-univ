<?php

namespace App\Filament\Admin\Resources\Enrollments\Schemas;

use App\Enums\Krs\EnrollmentStatus;
use App\Models\Student;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EnrollmentForm
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
                Section::make('Identitas KRS')
                    ->columnSpanFull()
                    ->schema([
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
                            ->disabled()
                            ->columnSpan(1),

                        Select::make('semester_id')
                            ->label('Semester')
                            ->relationship('semester', 'name', fn ($query) => $query->orderByDesc('is_active'))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->disabled()
                            ->columnSpan(1),

                        TextInput::make('max_sks')
                            ->label('SKS Maksimum')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(30)
                            ->default(24)
                            ->disabled()
                            ->columnSpan(1),

                        TextInput::make('total_sks_taken')
                            ->label('Total SKS Diambil')
                            ->numeric()
                            ->default(0)
                            ->disabled()
                            ->dehydrated()
                            ->helperText('Auto-update dari MK yang ditambahkan.')
                            ->columnSpan(1),
                    ])
                    ->columns(2),

                Section::make('Status & Approval')
                    ->description('Approver & tanggal approve otomatis terisi saat status diubah ke Approved. Saat Approved → auto Locked.')
                    ->columnSpanFull()
                    ->schema([
                        Select::make('status')
                            ->label('Status')
                            ->options(EnrollmentStatus::class)
                            ->default('Draft')
                            ->required()
                            ->columnSpan(1),

                        DateTimePicker::make('approved_at')
                            ->label('Tanggal Disetujui')
                            ->seconds(false)
                            ->native(false)
                            ->disabled()
                            ->dehydrated()
                            ->placeholder('Auto-fill saat status = Approved')
                            ->columnSpan(1),

                        Textarea::make('notes')
                            ->label('Catatan')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
