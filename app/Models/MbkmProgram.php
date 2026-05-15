<?php

namespace App\Models;

use App\Concerns\AutoGeneratesCode;
use App\Concerns\HasAuditable;
use App\Enums\Thesis\MbkmType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class MbkmProgram extends Model
{
    use AutoGeneratesCode, HasAuditable, LogsActivity;
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'type',
        'partner_name',
        'partner_address',
        'description',
        'total_sks',
        'start_date',
        'end_date',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'type' => MbkmType::class,
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('mbkm_programs');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(MbkmEnrollment::class);
    }
    public function codePrefix(): string
    {
        return 'MBKM';
    }
}
