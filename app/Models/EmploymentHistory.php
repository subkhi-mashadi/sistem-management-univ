<?php

namespace App\Models;

use App\Concerns\HasAuditable;
use App\Enums\Hris\EmploymentEventType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class EmploymentHistory extends Model
{
    use HasAuditable, LogsActivity;

    protected $fillable = [
        'employee_id',
        'event_type',
        'effective_date',
        'from_value',
        'to_value',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'event_type' => EmploymentEventType::class,
        'effective_date' => 'date',
        'from_value' => 'array',
        'to_value' => 'array',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('employment_histories');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
