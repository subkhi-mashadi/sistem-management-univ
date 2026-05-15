<?php

namespace App\Filament\Admin\Resources\Prerequisites\Schemas;

use App\Enums\Academic\LogicOperator;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PrerequisiteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('course_id')
                    ->label('Mata Kuliah')
                    ->relationship('course', 'name')
                    ->required(),
                Select::make('prerequisite_course_id')
                    ->label('MK Prasyarat')
                    ->relationship('prerequisiteCourse', 'name')
                    ->required(),
                TextInput::make('minimum_grade')
                    ->label('Nilai Minimum'),
                Select::make('logic_operator')
                    ->label('Operator')
                    ->options(LogicOperator::class)
                    ->default('AND')
                    ->required(),
                TextInput::make('group_no')
                    ->label('Grup')
                    ->required()
                    ->numeric()
                    ->default(1),
            ]);
    }
}
