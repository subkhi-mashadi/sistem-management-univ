<?php

namespace App\Models;

use App\Concerns\HasAuditable;
use App\Enums\Hris\SalaryStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Salary extends Model
{
    use HasAuditable, LogsActivity;

    protected $fillable = [
        'payroll_period_id',
        'employee_id',
        'base_salary',
        'total_allowance',
        'teaching_honor',
        'other_income',
        'gross_total',
        'bpjs_deduction',
        'tax_pph21',
        'other_deduction',
        'total_deduction',
        'net_total',
        'status',
        'paid_at',
        'slip_pdf_url',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'status' => SalaryStatus::class,
        'base_salary' => 'decimal:2',
        'total_allowance' => 'decimal:2',
        'teaching_honor' => 'decimal:2',
        'other_income' => 'decimal:2',
        'gross_total' => 'decimal:2',
        'bpjs_deduction' => 'decimal:2',
        'tax_pph21' => 'decimal:2',
        'other_deduction' => 'decimal:2',
        'total_deduction' => 'decimal:2',
        'net_total' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('salaries');
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(PayrollPeriod::class, 'payroll_period_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(SalaryDetail::class);
    }
}
