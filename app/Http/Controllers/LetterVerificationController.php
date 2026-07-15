<?php

namespace App\Http\Controllers;

use App\Enums\EOffice\LetterRequestStatus;
use App\Models\LetterRequest;
use Illuminate\View\View;

class LetterVerificationController extends Controller
{
    public function __invoke(string $qr_code): View
    {
        $letterRequest = LetterRequest::with(['template', 'student.user', 'requester'])
            ->where('qr_code', $qr_code)
            ->where('status', LetterRequestStatus::Issued)
            ->first();

        if (! $letterRequest) {
            return view('eoffice.verify', ['state' => 'invalid']);
        }

        if ($letterRequest->qr_valid_until && $letterRequest->qr_valid_until->isPast()) {
            return view('eoffice.verify', ['state' => 'expired']);
        }

        return view('eoffice.verify', ['state' => 'valid', 'letterRequest' => $letterRequest]);
    }
}
