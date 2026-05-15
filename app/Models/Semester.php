<?php

namespace App\Models;

use App\Concerns\AutoGeneratesCode;
use App\Concerns\HasAuditable;
use App\Enums\Academic\SemesterTerm;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Semester extends Model
{
    use AutoGeneratesCode, HasAuditable, HasFactory, LogsActivity;

    protected $fillable = [
        'academic_calendar_id',
        'code',
        'name',
        'term',
        'start_date',
        'end_date',
        'krs_start',
        'krs_end',
        'lecture_start',
        'lecture_end',
        'uts_start',
        'uts_end',
        'uas_start',
        'uas_end',
        'grade_input_deadline',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'term' => SemesterTerm::class,
        'start_date' => 'date',
        'end_date' => 'date',
        'krs_start' => 'date',
        'krs_end' => 'date',
        'lecture_start' => 'date',
        'lecture_end' => 'date',
        'uts_start' => 'date',
        'uts_end' => 'date',
        'uas_start' => 'date',
        'uas_end' => 'date',
        'grade_input_deadline' => 'date',
        'is_active' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty()->dontSubmitEmptyLogs()->useLogName('semesters');
    }

    public function academicCalendar(): BelongsTo
    {
        return $this->belongsTo(AcademicCalendar::class);
    }

    public function courseOfferings(): HasMany
    {
        return $this->hasMany(CourseOffering::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }
    public function codePrefix(): string
    {
        return 'SMT';
    }
}
