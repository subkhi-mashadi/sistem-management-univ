<?php

namespace App\Models;

use App\Concerns\HasAuditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Logbook extends Model
{
    use HasAuditable, LogsActivity;

    protected $fillable = [
        'thesis_topic_id',
        'student_id',
        'lecturer_id',
        'session_date',
        'duration_minutes',
        'topic_discussed',
        'progress_summary',
        'lecturer_feedback',
        'is_verified',
        'verified_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'session_date' => 'date',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('logbooks');
    }

    public function thesisTopic(): BelongsTo
    {
        return $this->belongsTo(ThesisTopic::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function lecturer(): BelongsTo
    {
        return $this->belongsTo(Lecturer::class);
    }
}
