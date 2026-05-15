<?php

namespace App\Models;

use App\Concerns\HasAuditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Grade extends Model
{
    use HasAuditable, HasFactory, LogsActivity;

    protected $fillable = [
        'student_id',
        'course_offering_id',
        'krs_item_id',
        'attendance_score',
        'assignment_score',
        'mid_score',
        'final_score',
        'extra_score',
        'total_score',
        'letter_grade',
        'grade_point',
        'is_locked',
        'locked_at',
        'locked_by',
        'submitted_by',
        'submitted_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'attendance_score' => 'decimal:2',
        'assignment_score' => 'decimal:2',
        'mid_score' => 'decimal:2',
        'final_score' => 'decimal:2',
        'extra_score' => 'decimal:2',
        'total_score' => 'decimal:2',
        'grade_point' => 'decimal:2',
        'is_locked' => 'boolean',
        'locked_at' => 'datetime',
        'submitted_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty()->dontSubmitEmptyLogs()->useLogName('grades');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function courseOffering(): BelongsTo
    {
        return $this->belongsTo(CourseOffering::class);
    }

    public function krsItem(): BelongsTo
    {
        return $this->belongsTo(KrsItem::class);
    }

    public function lockedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'locked_by');
    }

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }
}
