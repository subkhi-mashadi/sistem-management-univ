<?php

namespace App\Models;

use App\Concerns\HasAuditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class TaxCalculation extends Model
{
    use HasAuditable, LogsActivity;

    protected $fillable = [
        'payroll_period_id',
        'employee_id',
        'tax_year',
        'gross_year_to_date',
        'ptkp_category',
        'ptkp_amount',
        'taxable_income',
        'tax_pph21',
        'tax_pph21_ytd',
        'is_final',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'gross_year_to_date' => 'decimal:2',
        'ptkp_amount' => 'decimal:2',
        'taxable_income' => 'decimal:2',
        'tax_pph21' => 'decimal:2',
        'tax_pph21_ytd' => 'decimal:2',
        'is_final' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('tax_calculations');
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(PayrollPeriod::class, 'payroll_period_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
