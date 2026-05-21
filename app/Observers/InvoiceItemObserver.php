<?php

namespace App\Observers;

use App\Models\Invoice;
use App\Models\InvoiceItem;

/**
 * Saat item invoice berubah → otomatis recalc subtotal & total invoice.
 * Menjaga konsistensi: user tidak perlu hitung manual.
 */
class InvoiceItemObserver
{
    public function saved(InvoiceItem $item): void
    {
        $this->recalculate($item->invoice_id);
    }

    public function deleted(InvoiceItem $item): void
    {
        $this->recalculate($item->invoice_id);
    }

    private function recalculate(int $invoiceId): void
    {
        $invoice = Invoice::find($invoiceId);
        if (! $invoice) {
            return;
        }

        $subtotal = (float) InvoiceItem::where('invoice_id', $invoiceId)->sum('total');
        $total = $subtotal
            - (float) $invoice->discount_amount
            - (float) $invoice->scholarship_amount
            + (float) $invoice->fine_amount;

        $invoice->updateQuietly([
            'subtotal' => $subtotal,
            'total_amount' => max(0, $total),
        ]);
    }
}
