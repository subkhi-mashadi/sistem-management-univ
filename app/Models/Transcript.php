<?php

namespace App\Models;

use App\Concerns\HasAuditable;
use App\Enums\Krs\AcademicStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Transcript extends Model
{
    use HasAuditable, HasFactory, LogsActivity;

    protected $fillable = [
        'student_id',
        'semester_id',
        'sks_attempted',
        'sks_acquired',
        'semester_gpa',
        'cumulative_gpa',
        'sks_cumulative',
        'academic_status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'semester_gpa' => 'decimal:2',
        'cumulative_gpa' => 'decimal:2',
        'academic_status' => AcademicStatus::class,
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty()->dontSubmitEmptyLogs()->useLogName('transcripts');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }
}
