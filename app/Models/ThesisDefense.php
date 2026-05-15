<?php

namespace App\Models;

use App\Concerns\HasAuditable;
use App\Enums\Thesis\DefenseStatus;
use App\Enums\Thesis\DefenseType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ThesisDefense extends Model
{
    use HasAuditable, LogsActivity;
    use SoftDeletes;

    protected $fillable = [
        'thesis_topic_id',
        'defense_type',
        'scheduled_at',
        'room',
        'examiner_ids',
        'plagiarism_score',
        'final_score',
        'letter_grade',
        'status',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'defense_type' => DefenseType::class,
        'status' => DefenseStatus::class,
        'examiner_ids' => 'array',
        'plagiarism_score' => 'decimal:2',
        'final_score' => 'decimal:2',
        'scheduled_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('thesis_defenses');
    }

    public function thesisTopic(): BelongsTo
    {
        return $this->belongsTo(ThesisTopic::class);
    }
}
