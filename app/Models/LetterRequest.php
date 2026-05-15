<?php

namespace App\Models;

use App\Concerns\HasAuditable;
use App\Enums\EOffice\LetterRequestStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class LetterRequest extends Model
{
    use HasAuditable, LogsActivity, SoftDeletes;

    protected $fillable = [
        'letter_number',
        'letter_template_id',
        'workflow_id',
        'requester_id',
        'student_id',
        'form_data',
        'current_step_id',
        'status',
        'pdf_url',
        'qr_code',
        'qr_valid_until',
        'issued_at',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'status' => LetterRequestStatus::class,
        'form_data' => 'array',
        'qr_valid_until' => 'date',
        'issued_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('letter_requests');
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(LetterTemplate::class, 'letter_template_id');
    }

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function currentStep(): BelongsTo
    {
        return $this->belongsTo(WorkflowStep::class, 'current_step_id');
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(Approval::class);
    }
}
