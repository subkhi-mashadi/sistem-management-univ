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

    protected static function booted(): void
    {
        static::saving(function (self $refund) {
            // Auto-gen refund_number.
            if (empty($refund->refund_number)) {
                $refund->refund_number = self::generateRefundNumber();
            }

            // Auto-fill student_id dari payment kalau kosong.
            if (empty($refund->student_id) && $refund->payment_id) {
                $refund->student_id = Payment::where('id', $refund->payment_id)->value('student_id');
            }

            $status = $refund->status instanceof RefundStatus ? $refund->status->value : $refund->status;

            // Saat status berubah ke Approved → set approved_by + approved_at.
            if ($status === RefundStatus::Approved->value && empty($refund->approved_at)) {
                $refund->approved_at = now();
                if (empty($refund->approved_by) && auth()->check()) {
                    $refund->approved_by = auth()->id();
                }
            }

            // Saat status Disbursed → set disbursed_at.
            if ($status === RefundStatus::Disbursed->value && empty($refund->disbursed_at)) {
                $refund->disbursed_at = now();
            }
        });
    }

    public static function generateRefundNumber(): string
    {
        $prefix = 'REF-'.now()->format('Ym').'-';
        $lastSeq = (int) self::query()
            ->where('refund_number', 'like', $prefix.'%')
            ->withTrashed()
            ->get()
            ->map(fn (self $r) => (int) substr($r->refund_number, strrpos($r->refund_number, '-') + 1))
            ->max();

        return $prefix.str_pad((string) ($lastSeq + 1), 4, '0', STR_PAD_LEFT);
    }

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
