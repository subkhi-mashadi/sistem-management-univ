<?php

namespace App\Models;

use App\Concerns\HasAuditable;
use App\Enums\Academic\EducationLevel;
use App\Enums\Academic\EmploymentStatus;
use App\Enums\Academic\FunctionalPosition;
use App\Enums\Academic\StructuralPosition;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Lecturer extends Model
{
    use HasAuditable, HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'user_id',
        'nidn',
        'nidk',
        'nip',
        'study_program_id',
        'functional_position',
        'structural_position',
        'education_level',
        'expertise_keywords',
        'employment_status',
        'start_date',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'functional_position' => FunctionalPosition::class,
        'structural_position' => StructuralPosition::class,
        'education_level' => EducationLevel::class,
        'employment_status' => EmploymentStatus::class,
        'expertise_keywords' => 'array',
        'start_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty()->dontSubmitEmptyLogs()->useLogName('lecturers');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function studyProgram(): BelongsTo
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function advisees(): HasMany
    {
        return $this->hasMany(Student::class, 'academic_advisor_id');
    }

    public function thesisAdvisorships(): HasMany
    {
        return $this->hasMany(ThesisAdvisor::class);
    }
}
