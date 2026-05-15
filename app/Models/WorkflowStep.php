<?php

namespace App\Models;

use App\Concerns\HasAuditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class WorkflowStep extends Model
{
    use HasAuditable, LogsActivity;

    protected $fillable = [
        'workflow_id',
        'step_order',
        'name',
        'approver_role_id',
        'approver_user_id',
        'is_parallel',
        'can_reject',
        'sla_hours',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_parallel' => 'boolean',
        'can_reject' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('workflow_steps');
    }

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class);
    }

    public function approverUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_user_id');
    }
}
