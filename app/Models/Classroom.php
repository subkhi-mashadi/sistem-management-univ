<?php

namespace App\Models;

use App\Concerns\AutoGeneratesCode;
use App\Concerns\HasAuditable;
use App\Enums\Academic\RoomType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Classroom extends Model
{
    use AutoGeneratesCode, HasAuditable, HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'building',
        'floor',
        'capacity',
        'room_type',
        'facilities',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'room_type' => RoomType::class,
        'facilities' => 'array',
        'is_active' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty()->dontSubmitEmptyLogs()->useLogName('classrooms');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }
    public function codePrefix(): string
    {
        return 'RG';
    }
}
