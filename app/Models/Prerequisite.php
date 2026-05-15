<?php

namespace App\Models;

use App\Concerns\HasAuditable;
use App\Enums\Academic\LogicOperator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Prerequisite extends Model
{
    use HasAuditable, HasFactory, LogsActivity;

    protected $fillable = [
        'course_id',
        'prerequisite_course_id',
        'minimum_grade',
        'logic_operator',
        'group_no',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'logic_operator' => LogicOperator::class,
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty()->dontSubmitEmptyLogs()->useLogName('prerequisites');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function prerequisiteCourse(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'prerequisite_course_id');
    }
}
