<?php

namespace App\Services\EOffice;

use App\Models\LetterRequest;
use App\Models\LetterTemplate;
use Illuminate\Support\Facades\DB;

/**
 * Nomor surat full otomatis, format tetap: {KODE_TEMPLATE}-{YEAR}-{SEQ}
 * (SEQ = urutan 4 digit per template per tahun). Tidak ada pola manual.
 */
class LetterNumberGenerator
{
    public function generate(LetterRequest $request): string
    {
        $template = $request->template;
        $year = now()->year;
        $seq = $this->nextSequence($template, $year);

        return sprintf('%s-%d-%04d', $template?->code ?? 'LR', $year, $seq);
    }

    protected function nextSequence(?LetterTemplate $template, int $year): int
    {
        $query = LetterRequest::withoutGlobalScopes()
            ->whereYear('created_at', $year)
            ->lockForUpdate();

        if ($template) {
            $query->where('letter_template_id', $template->id);
        }

        return $query->count() + 1;
    }
}
