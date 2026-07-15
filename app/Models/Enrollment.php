<?php

namespace App\Models;

use App\Concerns\HasAuditable;
use App\Enums\Krs\EnrollmentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Enrollment extends Model
{
    use HasAuditable, HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'student_id',
        'semester_id',
        'max_sks',
        'total_sks_taken',
        'status',
        'approved_by',
        'approved_at',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'status' => EnrollmentStatus::class,
        'approved_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $enrollment) {
            $status = $enrollment->status instanceof EnrollmentStatus
                ? $enrollment->status->value
                : $enrollment->status;

            // Saat status berubah ke Approved → auto-set approved_by + approved_at.
            if ($status === EnrollmentStatus::Approved->value) {
                if (empty($enrollment->approved_at)) {
                    $enrollment->approved_at = now();
                }
                if (empty($enrollment->approved_by) && auth()->check()) {
                    $enrollment->approved_by = auth()->id();
                }
            }

            if (in_array($status, [EnrollmentStatus::Draft->value, EnrollmentStatus::Rejected->value], true)) {
                $enrollment->approved_at = null;
                $enrollment->approved_by = null;
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty()->dontSubmitEmptyLogs()->useLogName('enrollments');
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

    public function items(): HasMany
    {
        return $this->hasMany(KrsItem::class);
    }
}
