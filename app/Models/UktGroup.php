<?php

namespace App\Models;

use App\Concerns\HasAuditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class UktGroup extends Model
{
    use HasAuditable, LogsActivity, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'description',
        'min_income',
        'max_income',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'min_income' => 'decimal:2',
        'max_income' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public const CODE_PREFIX = 'GL-';

    protected static function booted(): void
    {
        static::creating(function (self $group) {
            if (empty($group->code)) {
                $group->code = self::generateNextCode();
            }
        });
    }

    public static function generateNextCode(): string
    {
        $lastNumber = (int) static::query()
            ->where('code', 'like', self::CODE_PREFIX.'%')
            ->withTrashed()
            ->get()
            ->map(fn (self $g) => (int) substr($g->code, strlen(self::CODE_PREFIX)))
            ->max();

        return self::CODE_PREFIX.str_pad((string) ($lastNumber + 1), 3, '0', STR_PAD_LEFT);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('ukt_groups');
    }
}
