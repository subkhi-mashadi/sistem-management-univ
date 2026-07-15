<?php

namespace App\Models;

use App\Concerns\HasAuditable;
use App\Enums\Academic\EntryPath;
use App\Enums\Academic\StudentStatus;
use App\Enums\Common\Gender;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Student extends Model
{
    use HasAuditable, HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'user_id',
        'nim',
        'study_program_id',
        'curriculum_id',
        'enrollment_year',
        'academic_advisor_id',
        'status',
        'entry_path',
        'ukt_group',
        'gender',
        'birth_place',
        'birth_date',
        'address',
        'phone',
        'parent_name',
        'parent_phone',
        'religion',
        'nationality',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'status' => StudentStatus::class,
        'entry_path' => EntryPath::class,
        'gender' => Gender::class,
        'birth_date' => 'date',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty()->dontSubmitEmptyLogs()->useLogName('students');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function studyProgram(): BelongsTo
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function curriculum(): BelongsTo
    {
        return $this->belongsTo(Curriculum::class);
    }

    public function advisor(): BelongsTo
    {
        return $this->belongsTo(Lecturer::class, 'academic_advisor_id');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function scholarshipRecipients(): HasMany
    {
        return $this->hasMany(ScholarshipRecipient::class);
    }

    public function thesisTopics(): HasMany
    {
        return $this->hasMany(ThesisTopic::class);
    }
}
