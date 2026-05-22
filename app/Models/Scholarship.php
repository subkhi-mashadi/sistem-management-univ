<?php

namespace App\Models;

use App\Concerns\AutoGeneratesCode;
use App\Concerns\HasAuditable;
use App\Enums\Finance\CoverageType;
use App\Enums\Finance\ScholarshipType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Scholarship extends Model
{
    use AutoGeneratesCode, HasAuditable, LogsActivity, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'type',
        'covered_components',
        'coverage_type',
        'coverage_value',
        'description',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'type' => ScholarshipType::class,
        'coverage_type' => CoverageType::class,
        'covered_components' => 'array',
        'coverage_value' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $scholarship) {
            $coverageType = $scholarship->coverage_type instanceof CoverageType
                ? $scholarship->coverage_type->value
                : $scholarship->coverage_type;

            if ($coverageType === CoverageType::Full->value) {
                $scholarship->coverage_value = 100;
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('scholarships');
    }

    public function recipients(): HasMany
    {
        return $this->hasMany(ScholarshipRecipient::class);
    }
    public function codePrefix(): string
    {
        return 'BS';
    }
}
