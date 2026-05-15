<?php

namespace App\Models;

use App\Concerns\AutoGeneratesCode;
use App\Concerns\HasAuditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class NotificationTemplate extends Model
{
    use AutoGeneratesCode, HasAuditable, LogsActivity;

    protected $fillable = [
        'code',
        'name',
        'event',
        'channels',
        'subject',
        'body_email',
        'body_wa',
        'body_push',
        'body_inapp',
        'variables',
        'is_mandatory',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'channels' => 'array',
        'variables' => 'array',
        'is_mandatory' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('notification_templates');
    }
    public function codePrefix(): string
    {
        return 'NTF';
    }
}
