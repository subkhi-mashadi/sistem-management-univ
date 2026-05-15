<?php

namespace App\Models;

use App\Concerns\HasAuditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class LecturerWorkload extends Model
{
    use HasAuditable, HasFactory, LogsActivity;

    protected $fillable = [
        'lecturer_id',
        'semester_id',
        'teaching_sks',
        'advisory_sks',
        'research_sks',
        'service_sks',
        'additional_sks',
        'total_sks',
        'is_locked',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'teaching_sks' => 'decimal:2',
        'advisory_sks' => 'decimal:2',
        'research_sks' => 'decimal:2',
        'service_sks' => 'decimal:2',
        'additional_sks' => 'decimal:2',
        'total_sks' => 'decimal:2',
        'is_locked' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty()->dontSubmitEmptyLogs()->useLogName('lecturer_workloads');
    }

    public function lecturer(): BelongsTo
    {
        return $this->belongsTo(Lecturer::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }
}
