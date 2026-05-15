<?php

namespace App\Concerns;

/**
 * Auto-generate `code` jika user tidak mengisinya.
 * Model harus override `codePrefix()` (atau pakai default dari class basename).
 * Format: <PREFIX>-<YYYY>-<NNNN>, mis. FAK-2026-0001.
 */
trait AutoGeneratesCode
{
    public static function bootAutoGeneratesCode(): void
    {
        static::creating(function ($model): void {
            if (! in_array('code', $model->getFillable(), true)) {
                return;
            }
            if (filled($model->code)) {
                return;
            }

            $model->code = static::generateNextCode($model);
        });
    }

    public static function generateNextCode($model): string
    {
        $prefix = method_exists($model, 'codePrefix')
            ? $model->codePrefix()
            : strtoupper(substr(class_basename($model), 0, 3));

        $year = now()->year;
        $likePattern = "{$prefix}-{$year}-%";

        $last = static::withoutGlobalScopes()
            ->where('code', 'like', $likePattern)
            ->orderByDesc('code')
            ->value('code');

        $next = 1;
        if ($last && preg_match('/-(\d+)$/', $last, $m)) {
            $next = (int) $m[1] + 1;
        }

        return sprintf('%s-%s-%04d', $prefix, $year, $next);
    }
}
