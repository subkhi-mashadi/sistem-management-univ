<?php

namespace App\Models;

use App\Concerns\AutoGeneratesCode;
use App\Concerns\HasAuditable;
use App\Enums\Finance\BillingComponentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class BillingComponent extends Model
{
    use AutoGeneratesCode, HasAuditable, LogsActivity, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'type',
        'is_recurring',
        'description',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'type' => BillingComponentType::class,
        'is_recurring' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('billing_components');
    }

    public function rates(): HasMany
    {
        return $this->hasMany(BillingRate::class);
    }

    public function invoiceItems(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }
    public function codePrefix(): string
    {
        return 'BIL';
    }
}
