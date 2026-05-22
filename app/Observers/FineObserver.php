<?php

namespace App\Observers;

use App\Models\Fine;
use App\Models\Invoice;

/**
 * Saat denda ditambah/diwaived/dihapus → recalc total invoice.
 * Denda yang waived tidak dihitung (lihat Invoice::recalculateTotals).
 */
class FineObserver
{
    public function saved(Fine $fine): void
    {
        Invoice::find($fine->invoice_id)?->recalculateTotals();
    }

    public function deleted(Fine $fine): void
    {
        Invoice::find($fine->invoice_id)?->recalculateTotals();
    }
}
