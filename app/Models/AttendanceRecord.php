<?php

namespace App\Models;

use App\Concerns\HasAuditable;
use App\Enums\Hris\EmployeeAttendanceStatus;
use App\Enums\Hris\EmployeeCheckMethod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class AttendanceRecord extends Model
{
    use HasAuditable, LogsActivity;

    protected $fillable = [
        'employee_id',
        'work_date',
        'check_in_at',
        'check_out_at',
        'check_in_method',
        'check_out_method',
        'check_in_location',
        'check_out_location',
        'late_minutes',
        'early_leave_minutes',
        'status',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'check_in_method' => EmployeeCheckMethod::class,
        'check_out_method' => EmployeeCheckMethod::class,
        'status' => EmployeeAttendanceStatus::class,
        'work_date' => 'date',
        'check_in_at' => 'datetime',
        'check_out_at' => 'datetime',
        'check_in_location' => 'array',
        'check_out_location' => 'array',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('attendance_records');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
