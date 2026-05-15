<?php

namespace App\Models;

use App\Concerns\HasAuditable;
use App\Enums\Thesis\ThesisAdvisorStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ThesisAdvisor extends Model
{
    use HasAuditable, LogsActivity;

    protected $fillable = [
        'thesis_topic_id',
        'lecturer_id',
        'advisor_order',
        'assigned_at',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'status' => ThesisAdvisorStatus::class,
        'assigned_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('thesis_advisors');
    }

    public function thesisTopic(): BelongsTo
    {
        return $this->belongsTo(ThesisTopic::class);
    }

    public function lecturer(): BelongsTo
    {
        return $this->belongsTo(Lecturer::class);
    }
}
