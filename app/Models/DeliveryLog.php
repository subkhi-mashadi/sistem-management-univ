<?php

namespace App\Models;

use App\Concerns\HasAuditable;
use App\Enums\Communication\DeliveryStatus;
use App\Enums\Communication\NotificationChannel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class DeliveryLog extends Model
{
    use HasAuditable, LogsActivity;

    protected $fillable = [
        'erp_notification_id',
        'announcement_id',
        'recipient_id',
        'channel',
        'provider',
        'provider_message_id',
        'status',
        'error_message',
        'attempts',
        'sent_at',
        'delivered_at',
        'read_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'channel' => NotificationChannel::class,
        'status' => DeliveryStatus::class,
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'read_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('delivery_logs');
    }

    public function notification(): BelongsTo
    {
        return $this->belongsTo(ErpNotification::class, 'erp_notification_id');
    }

    public function announcement(): BelongsTo
    {
        return $this->belongsTo(Announcement::class);
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }
}
