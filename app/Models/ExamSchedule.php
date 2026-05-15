<?php

namespace App\Models;

use App\Concerns\HasAuditable;
use App\Enums\Scheduling\ExamType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ExamSchedule extends Model
{
    use HasAuditable, HasFactory, LogsActivity;

    protected $fillable = [
        'course_offering_id',
        'classroom_id',
        'exam_type',
        'exam_date',
        'start_time',
        'end_time',
        'proctor_ids',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'exam_type' => ExamType::class,
        'exam_date' => 'date',
        'proctor_ids' => 'array',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty()->dontSubmitEmptyLogs()->useLogName('exam_schedules');
    }

    public function courseOffering(): BelongsTo
    {
        return $this->belongsTo(CourseOffering::class);
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }
}
