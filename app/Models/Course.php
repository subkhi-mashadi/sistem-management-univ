<?php

namespace App\Models;

use App\Concerns\AutoGeneratesCode;
use App\Concerns\HasAuditable;
use App\Enums\Academic\CourseType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Course extends Model
{
    use AutoGeneratesCode, HasAuditable, HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'curriculum_id',
        'code',
        'name',
        'name_en',
        'sks_theory',
        'sks_practice',
        'sks_field',
        'total_sks',
        'semester',
        'course_type',
        'description',
        'learning_outcomes',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'course_type' => CourseType::class,
        'learning_outcomes' => 'array',
        'is_active' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty()->dontSubmitEmptyLogs()->useLogName('courses');
    }

    public function curriculum(): BelongsTo
    {
        return $this->belongsTo(Curriculum::class);
    }

    public function prerequisites(): HasMany
    {
        return $this->hasMany(Prerequisite::class, 'course_id');
    }

    public function offerings(): HasMany
    {
        return $this->hasMany(CourseOffering::class);
    }
    public function codePrefix(): string
    {
        return 'MK';
    }
}
