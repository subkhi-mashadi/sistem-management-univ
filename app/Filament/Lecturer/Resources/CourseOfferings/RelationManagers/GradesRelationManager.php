<?php

namespace App\Filament\Lecturer\Resources\CourseOfferings\RelationManagers;

// Reuse exact same logic as admin panel
use App\Filament\Admin\Resources\CourseOfferings\RelationManagers\GradesRelationManager as AdminGradesRelationManager;

class GradesRelationManager extends AdminGradesRelationManager
{
    // Inherits everything — scope already limited by CourseOfferingResource::getEloquentQuery()
}
