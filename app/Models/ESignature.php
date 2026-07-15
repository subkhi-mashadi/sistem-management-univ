<?php

namespace App\Models;

use App\Concerns\HasAuditable;
use App\Enums\EOffice\SignatureProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ESignature extends Model
{
    use HasAuditable, LogsActivity;

    protected $table = 'e_signatures';

    protected $fillable = [
        'user_id',
        'image_path',
        'provider',
        'certificate_data',
        'certificate_serial',
        'valid_from',
        'valid_until',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'provider' => SignatureProvider::class,
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('e_signatures');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
