<?php

namespace App\Filament\Admin\Resources\Lecturers\Schemas;

use App\Enums\Academic\EducationLevel;
use App\Enums\Academic\EmploymentStatus;
use App\Enums\Academic\FunctionalPosition;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class LecturerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Pengguna')
                    ->relationship('user', 'name')
                    ->required(),
                TextInput::make('nidn')
                    ->label('NIDN'),
                TextInput::make('nidk')
                    ->label('NIDK'),
                TextInput::make('nip')
                    ->label('NIP'),
                Select::make('study_program_id')
                    ->label('Program Studi')
                    ->relationship('studyProgram', 'name'),
                Select::make('functional_position')
                    ->label('Jabatan Fungsional')
                    ->options(FunctionalPosition::class),
                TextInput::make('structural_position')
                    ->label('Jabatan Struktural'),
                Select::make('education_level')
                    ->label('Pendidikan')
                    ->options(EducationLevel::class),
                TextInput::make('expertise_keywords')
                    ->label('Bidang Keahlian'),
                Select::make('employment_status')
                    ->label('Status Kepegawaian')
                    ->options(EmploymentStatus::class),
                DatePicker::make('start_date')
                    ->label('Tanggal Mulai'),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->required(),
            ]);
    }
}
