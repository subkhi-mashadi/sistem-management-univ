<?php

namespace App\Observers;

use App\Enums\Finance\InvoiceStatus;
use App\Enums\Finance\PaymentStatus;
use App\Models\Invoice;
use App\Models\Payment;

/**
 * Saat payment berhasil → auto-update invoice.paid_amount & status.
 * Auto-generate payment_number jika kosong.
 */
class PaymentObserver
{
    public function creating(Payment $payment): void
    {
        if (empty($payment->payment_number)) {
            $latest = Payment::max('id') ?? 0;
            $payment->payment_number = 'PAY-'.now()->format('Ym').'-'.str_pad((string) ($latest + 1), 6, '0', STR_PAD_LEFT);
        }
    }

    public function saved(Payment $payment): void
    {
        $this->recalculateInvoice($payment->invoice_id);
    }

    public function deleted(Payment $payment): void
    {
        $this->recalculateInvoice($payment->invoice_id);
    }

    private function recalculateInvoice(int $invoiceId): void
    {
        $invoice = Invoice::find($invoiceId);
        if (! $invoice) {
            return;
        }

        $paid = (float) Payment::where('invoice_id', $invoiceId)
            ->where('status', PaymentStatus::Success->value)
            ->sum('amount');

        $status = match (true) {
            $paid <= 0 => $invoice->status,
            $paid >= (float) $invoice->total_amount => InvoiceStatus::Paid,
            default => InvoiceStatus::Partial,
        };

        $invoice->updateQuietly([
            'paid_amount' => $paid,
            'status' => $status->value ?? $status,
        ]);
    }
}
