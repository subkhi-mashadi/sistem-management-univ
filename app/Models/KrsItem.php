<?php

namespace App\Models;

use App\Concerns\HasAuditable;
use App\Enums\Krs\KrsItemStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class KrsItem extends Model
{
    use HasAuditable, HasFactory, LogsActivity;

    protected $fillable = [
        'enrollment_id',
        'course_offering_id',
        'status',
        'dropped_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'status' => KrsItemStatus::class,
        'dropped_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty()->dontSubmitEmptyLogs()->useLogName('krs_items');
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function courseOffering(): BelongsTo
    {
        return $this->belongsTo(CourseOffering::class);
    }
}
