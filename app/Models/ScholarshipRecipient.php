<?php

namespace App\Models;

use App\Concerns\HasAuditable;
use App\Enums\Finance\ScholarshipRecipientStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ScholarshipRecipient extends Model
{
    use HasAuditable, LogsActivity;

    protected $fillable = [
        'scholarship_id',
        'student_id',
        'semester_id',
        'start_date',
        'end_date',
        'status',
        'granted_amount',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'status' => ScholarshipRecipientStatus::class,
        'start_date' => 'date',
        'end_date' => 'date',
        'granted_amount' => 'decimal:2',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('scholarship_recipients');
    }

    public function scholarship(): BelongsTo
    {
        return $this->belongsTo(Scholarship::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }
}
