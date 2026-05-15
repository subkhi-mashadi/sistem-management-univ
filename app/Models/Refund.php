<?php

namespace App\Models;

use App\Concerns\HasAuditable;
use App\Enums\Finance\RefundReason;
use App\Enums\Finance\RefundStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Refund extends Model
{
    use HasAuditable, LogsActivity, SoftDeletes;

    protected $fillable = [
        'refund_number',
        'payment_id',
        'student_id',
        'amount',
        'reason',
        'status',
        'approved_by',
        'approved_at',
        'disbursed_at',
        'bank_account',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'reason' => RefundReason::class,
        'status' => RefundStatus::class,
        'amount' => 'decimal:2',
        'approved_at' => 'datetime',
        'disbursed_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('refunds');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
