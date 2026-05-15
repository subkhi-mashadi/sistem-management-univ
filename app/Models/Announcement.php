<?php

namespace App\Models;

use App\Concerns\HasAuditable;
use App\Enums\Communication\AnnouncementStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Announcement extends Model
{
    use HasAuditable, LogsActivity;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'body',
        'audience_filter',
        'channels',
        'scheduled_at',
        'sent_at',
        'is_emergency',
        'requires_approval',
        'approved_by',
        'approved_at',
        'recipient_count',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'status' => AnnouncementStatus::class,
        'audience_filter' => 'array',
        'channels' => 'array',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'approved_at' => 'datetime',
        'is_emergency' => 'boolean',
        'requires_approval' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('announcements');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function deliveries(): HasMany
    {
        return $this->hasMany(DeliveryLog::class);
    }
}
