<?php

namespace App\Models;

use App\Concerns\HasAuditable;
use App\Enums\Common\Gender;
use App\Enums\Hris\EmployeeStatus;
use App\Enums\Hris\EmployeeType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Employee extends Model
{
    use HasAuditable, LogsActivity;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'nip',
        'nidn',
        'nik',
        'full_name',
        'gender',
        'birth_place',
        'birth_date',
        'address',
        'phone',
        'employee_type',
        'is_lecturer',
        'structural_position',
        'functional_position',
        'rank_grade',
        'unit_id',
        'base_salary',
        'bank_name',
        'bank_account',
        'npwp',
        'bpjs_kesehatan',
        'bpjs_ketenagakerjaan',
        'start_date',
        'end_date',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'gender' => Gender::class,
        'employee_type' => EmployeeType::class,
        'status' => EmployeeStatus::class,
        'birth_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        'base_salary' => 'decimal:2',
        'is_lecturer' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('employees');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(EmploymentHistory::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    public function leaves(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function salaries(): HasMany
    {
        return $this->hasMany(Salary::class);
    }
}
