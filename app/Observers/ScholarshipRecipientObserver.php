<?php

namespace App\Observers;

use App\Models\Invoice;
use App\Models\ScholarshipRecipient;

/**
 * Saat penerima beasiswa ditambah/diubah/dihapus → recalc invoice mahasiswa
 * untuk semester terkait (kalau ada invoice-nya).
 */
class ScholarshipRecipientObserver
{
    public function saved(ScholarshipRecipient $recipient): void
    {
        $this->refreshInvoice($recipient);

        // Kalau semester berubah (saat update), refresh invoice semester lama juga.
        if ($recipient->wasChanged('semester_id')) {
            $oldSemesterId = $recipient->getOriginal('semester_id');
            $this->refreshInvoiceById($recipient->student_id, $oldSemesterId);
        }
    }

    public function deleted(ScholarshipRecipient $recipient): void
    {
        $this->refreshInvoice($recipient);
    }

    private function refreshInvoice(ScholarshipRecipient $recipient): void
    {
        $this->refreshInvoiceById($recipient->student_id, $recipient->semester_id);
    }

    private function refreshInvoiceById(?int $studentId, ?int $semesterId): void
    {
        if (! $studentId || ! $semesterId) {
            return;
        }

        Invoice::where('student_id', $studentId)
            ->where('semester_id', $semesterId)
            ->get()
            ->each(fn (Invoice $inv) => $inv->recalculateTotals());
    }
}
