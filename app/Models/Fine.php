<?php

namespace App\Models;

use App\Concerns\HasAuditable;
use App\Enums\Finance\FineType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Fine extends Model
{
    use HasAuditable, LogsActivity;

    protected $fillable = [
        'invoice_id',
        'type',
        'amount',
        'description',
        'waived',
        'waived_by',
        'waived_at',
        'waive_reason',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'type' => FineType::class,
        'amount' => 'decimal:2',
        'waived' => 'boolean',
        'waived_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $fine) {
            // Saat di-toggle waived = true → otomatis isi waived_by + waived_at.
            if ($fine->waived && empty($fine->waived_at)) {
                $fine->waived_at = now();
                if (empty($fine->waived_by) && auth()->check()) {
                    $fine->waived_by = auth()->id();
                }
            }
            // Saat waived dimatikan → clear timestamp & approver.
            if (! $fine->waived) {
                $fine->waived_at = null;
                $fine->waived_by = null;
                $fine->waive_reason = null;
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('fines');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function waivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'waived_by');
    }
}
