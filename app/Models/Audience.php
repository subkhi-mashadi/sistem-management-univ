<?php

namespace App\Models;

use App\Concerns\HasAuditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Audience extends Model
{
    use HasAuditable, LogsActivity;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'filter_rules',
        'is_dynamic',
        'last_count',
        'last_resolved_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'filter_rules' => 'array',
        'is_dynamic' => 'boolean',
        'last_resolved_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('audiences');
    }
}
