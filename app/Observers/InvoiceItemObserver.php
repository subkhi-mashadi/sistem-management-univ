<?php

namespace App\Observers;

use App\Models\Invoice;
use App\Models\InvoiceItem;

/**
 * Saat item invoice berubah → recalc total invoice.
 */
class InvoiceItemObserver
{
    public function saved(InvoiceItem $item): void
    {
        Invoice::find($item->invoice_id)?->recalculateTotals();
    }

    public function deleted(InvoiceItem $item): void
    {
        Invoice::find($item->invoice_id)?->recalculateTotals();
    }
}
