<?php

namespace App\Models;

use App\Concerns\HasAuditable;
use App\Enums\EOffice\ApprovalAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Approval extends Model
{
    use HasAuditable, LogsActivity;

    protected $fillable = [
        'letter_request_id',
        'workflow_step_id',
        'approver_id',
        'action',
        'comments',
        'acted_at',
        'delegated_to',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'action' => ApprovalAction::class,
        'acted_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('approvals');
    }

    public function letterRequest(): BelongsTo
    {
        return $this->belongsTo(LetterRequest::class);
    }

    public function step(): BelongsTo
    {
        return $this->belongsTo(WorkflowStep::class, 'workflow_step_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function delegate(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delegated_to');
    }
}
