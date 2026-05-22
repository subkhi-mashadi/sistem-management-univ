<?php

namespace App\Models;

use App\Concerns\HasAuditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Discount extends Model
{
    use HasAuditable, LogsActivity;

    protected $fillable = [
        'invoice_id',
        'code',
        'reason',
        'amount',
        'approved_by',
        'approved_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $discount) {
            if (empty($discount->code)) {
                $discount->code = self::generateCode();
            }
            // Saat dibuat dengan amount > 0, anggap auto-approved oleh user pembuat.
            if (empty($discount->approved_by) && auth()->check()) {
                $discount->approved_by = auth()->id();
            }
            if (empty($discount->approved_at)) {
                $discount->approved_at = now();
            }
        });
    }

    public static function generateCode(): string
    {
        $prefix = 'DSK-'.now()->format('Ym').'-';
        $lastSeq = (int) self::query()
            ->where('code', 'like', $prefix.'%')
            ->get()
            ->map(fn (self $d) => (int) substr($d->code, strrpos($d->code, '-') + 1))
            ->max();

        return $prefix.str_pad((string) ($lastSeq + 1), 4, '0', STR_PAD_LEFT);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('discounts');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
