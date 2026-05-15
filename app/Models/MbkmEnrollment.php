<?php

namespace App\Models;

use App\Concerns\HasAuditable;
use App\Enums\Thesis\MbkmEnrollmentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class MbkmEnrollment extends Model
{
    use HasAuditable, LogsActivity;

    protected $fillable = [
        'mbkm_program_id',
        'student_id',
        'semester_id',
        'status',
        'approved_by',
        'approved_at',
        'final_score',
        'report_url',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'status' => MbkmEnrollmentStatus::class,
        'approved_at' => 'datetime',
        'final_score' => 'decimal:2',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('mbkm_enrollments');
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(MbkmProgram::class, 'mbkm_program_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function conversions(): HasMany
    {
        return $this->hasMany(SksConversion::class);
    }
}
