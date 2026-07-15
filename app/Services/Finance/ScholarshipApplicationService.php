<?php

namespace App\Services\Finance;

use App\Enums\Finance\CoverageType;
use App\Enums\Finance\ScholarshipRecipientStatus;
use App\Models\Invoice;
use App\Models\Scholarship;
use App\Models\ScholarshipRecipient;
use App\Models\Semester;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ScholarshipApplicationService
{
    public function apply(Scholarship $scholarship, Student $student, Semester $semester): ScholarshipRecipient
    {
        $exists = ScholarshipRecipient::where('scholarship_id', $scholarship->id)
            ->where('student_id', $student->id)
            ->where('semester_id', $semester->id)
            ->whereIn('status', [ScholarshipRecipientStatus::Pending, ScholarshipRecipientStatus::Active])
            ->exists();

        if ($exists) {
            throw new RuntimeException('Kamu sudah punya pengajuan/beasiswa aktif untuk beasiswa ini di semester ini.');
        }

        return ScholarshipRecipient::create([
            'scholarship_id' => $scholarship->id,
            'student_id' => $student->id,
            'semester_id' => $semester->id,
            'start_date' => $semester->start_date,
            'status' => ScholarshipRecipientStatus::Pending,
        ]);
    }

    public function approve(ScholarshipRecipient $recipient): ScholarshipRecipient
    {
        if ($recipient->status !== ScholarshipRecipientStatus::Pending) {
            throw new RuntimeException('Hanya pengajuan berstatus Pending yang bisa disetujui.');
        }

        return DB::transaction(function () use ($recipient) {
            $recipient = ScholarshipRecipient::whereKey($recipient->id)->lockForUpdate()->firstOrFail();
            $scholarship = $recipient->scholarship;

            $recipient->granted_amount = $this->calculateGrantedAmount($scholarship, $recipient);
            $recipient->status = ScholarshipRecipientStatus::Active;
            $recipient->save();

            return $recipient->fresh();
        });
    }

    public function reject(ScholarshipRecipient $recipient, ?string $reason = null): ScholarshipRecipient
    {
        if ($recipient->status !== ScholarshipRecipientStatus::Pending) {
            throw new RuntimeException('Hanya pengajuan berstatus Pending yang bisa ditolak.');
        }

        $recipient->status = ScholarshipRecipientStatus::Rejected;
        $recipient->notes = $reason;
        $recipient->save();

        return $recipient->fresh();
    }

    protected function calculateGrantedAmount(Scholarship $scholarship, ScholarshipRecipient $recipient): float
    {
        $invoice = Invoice::where('student_id', $recipient->student_id)
            ->where('semester_id', $recipient->semester_id)
            ->first();

        $baseAmount = 0.0;

        if ($invoice) {
            $baseAmount = empty($scholarship->covered_components)
                ? (float) $invoice->subtotal
                : (float) $invoice->items()
                    ->whereHas('component', fn ($q) => $q->whereIn('code', $scholarship->covered_components))
                    ->sum('total');
        }

        return match ($scholarship->coverage_type) {
            CoverageType::Full => $baseAmount,
            CoverageType::PartialPercent => round($baseAmount * ((float) $scholarship->coverage_value / 100), 2),
            CoverageType::PartialAmount => (float) $scholarship->coverage_value,
        };
    }
}
