<?php

namespace App\Observers;

use App\Models\Discount;
use App\Models\Invoice;

/**
 * Saat diskon ditambah/diubah/dihapus → recalc total invoice terkait.
 */
class DiscountObserver
{
    public function saved(Discount $discount): void
    {
        Invoice::find($discount->invoice_id)?->recalculateTotals();
    }

    public function deleted(Discount $discount): void
    {
        Invoice::find($discount->invoice_id)?->recalculateTotals();
    }
}
