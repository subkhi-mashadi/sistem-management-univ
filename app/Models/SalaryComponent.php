<?php

namespace App\Models;

use App\Concerns\AutoGeneratesCode;
use App\Concerns\HasAuditable;
use App\Enums\Hris\CalculationType;
use App\Enums\Hris\SalaryComponentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SalaryComponent extends Model
{
    use AutoGeneratesCode, HasAuditable, LogsActivity;

    protected $fillable = [
        'code',
        'name',
        'type',
        'calculation_type',
        'formula',
        'is_taxable',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'type' => SalaryComponentType::class,
        'calculation_type' => CalculationType::class,
        'is_taxable' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('salary_components');
    }
    public function codePrefix(): string
    {
        return 'SAL';
    }
}
