<?php

namespace App\Models;

use App\Concerns\HasAuditable;
use App\Enums\Krs\AppealStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class GradeAppeal extends Model
{
    use HasAuditable, HasFactory, LogsActivity;

    protected $fillable = [
        'grade_id',
        'student_id',
        'reason',
        'original_letter',
        'revised_letter',
        'original_score',
        'revised_score',
        'status',
        'reviewed_by',
        'reviewed_at',
        'review_notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'status' => AppealStatus::class,
        'original_score' => 'decimal:2',
        'revised_score' => 'decimal:2',
        'reviewed_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty()->dontSubmitEmptyLogs()->useLogName('grade_appeals');
    }

    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
