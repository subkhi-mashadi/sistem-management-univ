<?php

namespace App\Models;

use App\Concerns\HasAuditable;
use App\Enums\Thesis\ThesisTopicStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ThesisTopic extends Model
{
    use HasAuditable, LogsActivity;
    use SoftDeletes;

    protected $fillable = [
        'student_id',
        'title',
        'title_en',
        'abstract',
        'keywords',
        'research_field',
        'similarity_score',
        'status',
        'approved_by',
        'approved_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'keywords' => 'array',
        'similarity_score' => 'decimal:2',
        'status' => ThesisTopicStatus::class,
        'approved_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('thesis_topics');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function advisors(): HasMany
    {
        return $this->hasMany(ThesisAdvisor::class);
    }

    public function logbooks(): HasMany
    {
        return $this->hasMany(Logbook::class);
    }

    public function defenses(): HasMany
    {
        return $this->hasMany(ThesisDefense::class);
    }
}
