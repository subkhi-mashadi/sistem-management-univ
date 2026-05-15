<?php

namespace App\Models;

use App\Concerns\HasAuditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class LetterArchive extends Model
{
    use HasAuditable, LogsActivity;

    protected $fillable = [
        'letter_request_id',
        'letter_number',
        'category',
        'subject',
        'pdf_url',
        'searchable_text',
        'metadata',
        'archived_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'metadata' => 'array',
        'archived_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('letter_archives');
    }

    public function letterRequest(): BelongsTo
    {
        return $this->belongsTo(LetterRequest::class);
    }
}
