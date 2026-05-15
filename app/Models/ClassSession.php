<?php

namespace App\Models;

use App\Concerns\HasAuditable;
use App\Enums\Scheduling\ClassSessionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ClassSession extends Model
{
    use HasAuditable, HasFactory, LogsActivity;

    protected $fillable = [
        'schedule_id',
        'meeting_number',
        'session_date',
        'start_time',
        'end_time',
        'topic',
        'material_summary',
        'status',
        'substitute_lecturer_id',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'status' => ClassSessionStatus::class,
        'session_date' => 'date',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty()->dontSubmitEmptyLogs()->useLogName('class_sessions');
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function substituteLecturer(): BelongsTo
    {
        return $this->belongsTo(Lecturer::class, 'substitute_lecturer_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}
