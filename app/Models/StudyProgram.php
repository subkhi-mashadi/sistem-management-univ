<?php

namespace App\Models;

use App\Concerns\AutoGeneratesCode;
use App\Concerns\HasAuditable;
use App\Enums\Academic\Accreditation;
use App\Enums\Academic\DegreeLevel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StudyProgram extends Model
{
    use AutoGeneratesCode, HasAuditable, HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'faculty_id',
        'code',
        'name',
        'degree_level',
        'pddikti_code',
        'accreditation',
        'accreditation_valid_until',
        'head_id',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'degree_level' => DegreeLevel::class,
        'accreditation' => Accreditation::class,
        'accreditation_valid_until' => 'date',
        'is_active' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty()->dontSubmitEmptyLogs()->useLogName('study_programs');
    }

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function head(): BelongsTo
    {
        return $this->belongsTo(User::class, 'head_id');
    }

    public function curriculums(): HasMany
    {
        return $this->hasMany(Curriculum::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function lecturers(): HasMany
    {
        return $this->hasMany(Lecturer::class);
    }
    public function codePrefix(): string
    {
        return 'PRD';
    }
}
