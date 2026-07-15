<?php

namespace App\Services\EOffice;

use App\Enums\EOffice\ApprovalAction;
use App\Enums\EOffice\LetterRequestStatus;
use App\Models\ESignature;
use App\Models\LetterArchive;
use App\Models\LetterRequest;
use App\Models\LetterTemplate;
use App\Models\User;
use App\Support\EOffice\LetterSystemFields;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class LetterIssuanceService
{
    public function issue(LetterRequest $request): LetterRequest
    {
        if ($request->status !== LetterRequestStatus::Approved) {
            throw new RuntimeException('Hanya pengajuan berstatus Approved yang bisa diterbitkan.');
        }

        return DB::transaction(function () use ($request) {
            $request = LetterRequest::whereKey($request->id)->lockForUpdate()->firstOrFail();
            $template = $request->template;

            $qrCode = $this->uniqueQrCode();
            $issuedAt = now();
            $qrValidUntil = $issuedAt->copy()->addYear();

            $request->qr_code = $qrCode;
            $request->qr_valid_until = $qrValidUntil;
            $request->issued_at = $issuedAt;
            $request->status = LetterRequestStatus::Issued;
            $request->pdf_url = 'letters/'.$request->letter_number.'.pdf';

            $searchableText = $this->buildPdfFile($request, $template, $qrCode);

            $request->save();

            LetterArchive::create([
                'letter_request_id' => $request->id,
                'letter_number' => $request->letter_number,
                'category' => $template->category?->value ?? (string) $template->category,
                'subject' => $template->name,
                'pdf_url' => $request->pdf_url,
                'searchable_text' => $searchableText,
                'metadata' => $request->form_data,
                'archived_at' => $issuedAt,
            ]);

            return $request->fresh();
        });
    }

    /**
     * Render ulang PDF surat yang sudah Issued (pakai template body & data terkini)
     * tanpa mengubah letter_number/qr_code/issued_at — buat kasus template diedit
     * setelah surat lama terlanjur diterbitkan.
     */
    public function reprint(LetterRequest $request): LetterRequest
    {
        if ($request->status !== LetterRequestStatus::Issued) {
            throw new RuntimeException('Hanya surat berstatus Issued yang bisa dicetak ulang.');
        }

        return DB::transaction(function () use ($request) {
            $request = LetterRequest::whereKey($request->id)->lockForUpdate()->firstOrFail();
            $template = $request->template;

            $searchableText = $this->buildPdfFile($request, $template, $request->qr_code);

            LetterArchive::where('letter_request_id', $request->id)->update([
                'searchable_text' => $searchableText,
                'metadata' => $request->form_data,
            ]);

            return $request->fresh();
        });
    }

    /** @return string searchable text buat LetterArchive (intro + penutup, tanpa HTML). */
    protected function buildPdfFile(LetterRequest $request, LetterTemplate $template, string $qrCode): string
    {
        $verifyUrl = route('letter.verify', $qrCode);
        $qrBase64 = base64_encode(QrCode::format('png')->size(150)->generate($verifyUrl));

        [$signerName, $signerTitle, $signerId] = $this->resolveSigner($request);
        $signatureImageBase64 = $this->resolveSignatureImage($signerId);

        $dataLabels = LetterSystemFields::options();
        foreach (($template->custom_fields ?? []) as $field) {
            if (! empty($field['key'])) {
                $dataLabels[$field['key']] = $field['label'] ?? $field['key'];
            }
        }

        $pdf = Pdf::loadView('pdf.letter', [
            'request' => $request,
            'template' => $template,
            'introText' => $template->body,
            'closingText' => $template->closing_text,
            'dataLabels' => $dataLabels,
            'qrBase64' => $qrBase64,
            'verifyUrl' => $verifyUrl,
            'issuedAt' => $request->issued_at ?? now(),
            'qrValidUntil' => $request->qr_valid_until ?? now()->addYear(),
            'signerName' => $signerName,
            'signerTitle' => $signerTitle,
            'signatureImageBase64' => $signatureImageBase64,
        ])->setPaper('a4', 'portrait');

        $path = 'letters/'.$request->letter_number.'.pdf';
        Storage::disk('public')->put($path, $pdf->output());

        return strip_tags(trim(($template->body ?? '')."\n".($template->closing_text ?? '')));
    }

    /** @return array{0: string, 1: string, 2: int|null} [nama, jabatan, user_id] penandatangan — approver step terakhir yang menyetujui. */
    protected function resolveSigner(LetterRequest $request): array
    {
        $lastApproval = $request->approvals()
            ->where('action', ApprovalAction::Approved)
            ->with(['approver', 'step.approverRole'])
            ->orderByDesc('acted_at')
            ->first();

        if (! $lastApproval || ! $lastApproval->approver) {
            return ['(Pejabat Berwenang)', 'Pejabat Berwenang', null];
        }

        /** @var User $approver */
        $approver = $lastApproval->approver;
        $title = $lastApproval->step?->approverRole?->name
            ?? $lastApproval->step?->name
            ?? 'Pejabat Berwenang';

        return [$approver->name, $title, $approver->id];
    }

    protected function resolveSignatureImage(?int $userId): ?string
    {
        if (! $userId) {
            return null;
        }

        $signature = ESignature::where('user_id', $userId)
            ->where('is_active', true)
            ->whereNotNull('image_path')
            ->first();

        if (! $signature || ! Storage::disk('public')->exists($signature->image_path)) {
            return null;
        }

        $mime = Storage::disk('public')->mimeType($signature->image_path) ?: 'image/png';

        return 'data:'.$mime.';base64,'.base64_encode(Storage::disk('public')->get($signature->image_path));
    }

    protected function uniqueQrCode(): string
    {
        do {
            $code = Str::random(40);
        } while (LetterRequest::where('qr_code', $code)->exists());

        return $code;
    }
}
