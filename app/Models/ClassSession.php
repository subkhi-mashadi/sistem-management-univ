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
        'classroom_id',
        'meeting_number',
        'session_date',
        'start_time',
        'end_time',
        'topic',
        'material_summary',
        'status',
        'substitute_lecturer_id',
        'notes',
        'material_files',
        'attendance_token',
        'token_expires_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'status'           => ClassSessionStatus::class,
        'session_date'     => 'date',
        'token_expires_at' => 'datetime',
        'material_files'   => 'array',
    ];

    public function generateAttendanceToken(int $minutesTtl = 30): self
    {
        $this->update([
            'attendance_token' => strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 6)),
            'token_expires_at' => now()->addMinutes($minutesTtl),
        ]);

        return $this;
    }

    public function isTokenActive(): bool
    {
        return $this->attendance_token !== null
            && $this->token_expires_at !== null
            && $this->token_expires_at->isFuture();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty()->dontSubmitEmptyLogs()->useLogName('class_sessions');
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
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
