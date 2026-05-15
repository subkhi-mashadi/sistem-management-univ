<?php

namespace App\Models;

use App\Concerns\HasAuditable;
use App\Enums\Scheduling\DayOfWeek;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Schedule extends Model
{
    use HasAuditable, HasFactory, LogsActivity;

    protected $fillable = [
        'course_offering_id',
        'classroom_id',
        'day_of_week',
        'start_time',
        'end_time',
        'meeting_count',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'day_of_week' => DayOfWeek::class,
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty()->dontSubmitEmptyLogs()->useLogName('schedules');
    }

    public function courseOffering(): BelongsTo
    {
        return $this->belongsTo(CourseOffering::class);
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(ClassSession::class);
    }
}
