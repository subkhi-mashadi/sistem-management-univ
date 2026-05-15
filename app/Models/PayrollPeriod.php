<?php

namespace App\Models;

use App\Concerns\AutoGeneratesCode;
use App\Concerns\HasAuditable;
use App\Enums\Hris\PayrollPeriodStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PayrollPeriod extends Model
{
    use AutoGeneratesCode, HasAuditable, LogsActivity;

    protected $fillable = [
        'code',
        'name',
        'month',
        'year',
        'period_start',
        'period_end',
        'cutoff_date',
        'payment_date',
        'status',
        'closed_at',
        'closed_by',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'status' => PayrollPeriodStatus::class,
        'period_start' => 'date',
        'period_end' => 'date',
        'cutoff_date' => 'date',
        'payment_date' => 'date',
        'closed_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('payroll_periods');
    }

    public function closedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function salaries(): HasMany
    {
        return $this->hasMany(Salary::class);
    }

    public function teachingHonors(): HasMany
    {
        return $this->hasMany(TeachingHonor::class);
    }
    public function codePrefix(): string
    {
        return 'PYR';
    }
}
