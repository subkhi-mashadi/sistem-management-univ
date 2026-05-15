<?php

namespace App\Models;

use App\Concerns\AutoGeneratesCode;
use App\Concerns\HasAuditable;
use App\Enums\EOffice\LetterCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class LetterTemplate extends Model
{
    use AutoGeneratesCode, HasAuditable, LogsActivity, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'category',
        'body',
        'numbering_pattern',
        'requires_signature',
        'default_workflow_id',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'category' => LetterCategory::class,
        'requires_signature' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('letter_templates');
    }

    public function defaultWorkflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class, 'default_workflow_id');
    }

    public function requests(): HasMany
    {
        return $this->hasMany(LetterRequest::class);
    }
    public function codePrefix(): string
    {
        return 'LT';
    }
}
