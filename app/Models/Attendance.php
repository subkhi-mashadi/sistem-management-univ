<?php

namespace App\Models;

use App\Concerns\HasAuditable;
use App\Enums\Scheduling\AttendanceStatus;
use App\Enums\Scheduling\CheckMethod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Attendance extends Model
{
    use HasAuditable, HasFactory, LogsActivity;

    protected $fillable = [
        'class_session_id',
        'student_id',
        'status',
        'check_method',
        'check_in_at',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'status' => AttendanceStatus::class,
        'check_method' => CheckMethod::class,
        'check_in_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty()->dontSubmitEmptyLogs()->useLogName('attendances');
    }

    public function classSession(): BelongsTo
    {
        return $this->belongsTo(ClassSession::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
