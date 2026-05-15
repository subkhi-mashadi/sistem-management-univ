<?php

namespace App\Models;

use App\Concerns\HasAuditable;
use App\Enums\Communication\NotificationChannel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ErpNotification extends Model
{
    use HasAuditable, LogsActivity;

    protected $table = 'erp_notifications';

    protected $fillable = [
        'recipient_id',
        'template_code',
        'announcement_id',
        'title',
        'body',
        'channel',
        'is_read',
        'read_at',
        'payload',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'channel' => NotificationChannel::class,
        'payload' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('erp_notifications');
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function announcement(): BelongsTo
    {
        return $this->belongsTo(Announcement::class);
    }
}
