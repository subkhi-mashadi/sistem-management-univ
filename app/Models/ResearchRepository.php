<?php

namespace App\Models;

use App\Concerns\HasAuditable;
use App\Enums\Thesis\ResearchCategory;
use App\Enums\Thesis\ResearchReviewStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ResearchRepository extends Model
{
    use HasAuditable, LogsActivity;
    use SoftDeletes;

    protected $table = 'research_repository';

    protected $fillable = [
        'student_id',
        'lecturer_id',
        'title',
        'abstract',
        'keywords',
        'category',
        'publish_year',
        'doi',
        'file_url',
        'is_published',
        'review_status',
        'reviewed_by',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'category' => ResearchCategory::class,
        'review_status' => ResearchReviewStatus::class,
        'keywords' => 'array',
        'is_published' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('research_repository');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function lecturer(): BelongsTo
    {
        return $this->belongsTo(Lecturer::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
