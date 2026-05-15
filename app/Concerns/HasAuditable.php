<?php

namespace App\Concerns;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

/**
 * Auto-isi kolom `created_by` dan `updated_by` dari auth()->id().
 * Pasang di model yang punya kedua kolom tersebut.
 */
trait HasAuditable
{
    public static function bootHasAuditable(): void
    {
        static::creating(function ($model): void {
            $userId = Auth::id();
            if ($userId === null) {
                return;
            }

            if (empty($model->created_by) && in_array('created_by', $model->getFillable(), true)) {
                $model->created_by = $userId;
            }
            if (empty($model->updated_by) && in_array('updated_by', $model->getFillable(), true)) {
                $model->updated_by = $userId;
            }
        });

        static::updating(function ($model): void {
            $userId = Auth::id();
            if ($userId === null) {
                return;
            }

            if (in_array('updated_by', $model->getFillable(), true)) {
                $model->updated_by = $userId;
            }
        });
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }
}
