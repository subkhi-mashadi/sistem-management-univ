<?php

namespace App\Models;

use App\Concerns\AutoGeneratesCode;
use App\Concerns\HasAuditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Curriculum extends Model
{
    use AutoGeneratesCode, HasAuditable, HasFactory, LogsActivity, SoftDeletes;

    protected $table = 'curriculums';

    protected $fillable = [
        'study_program_id',
        'code',
        'name',
        'version',
        'effective_year',
        'total_sks_required',
        'is_active',
        'is_archived',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_archived' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty()->dontSubmitEmptyLogs()->useLogName('curriculums');
    }

    public function studyProgram(): BelongsTo
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
    public function codePrefix(): string
    {
        return 'KUR';
    }
}
