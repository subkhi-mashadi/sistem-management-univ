<?php

namespace App\Models;

use App\Concerns\AutoGeneratesCode;
use App\Concerns\HasAuditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Workflow extends Model
{
    use AutoGeneratesCode, HasAuditable, LogsActivity, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'description',
        'entity_type',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('workflows');
    }

    public function steps(): HasMany
    {
        return $this->hasMany(WorkflowStep::class);
    }

    public function requests(): HasMany
    {
        return $this->hasMany(LetterRequest::class);
    }
    public function codePrefix(): string
    {
        return 'WF';
    }
}
