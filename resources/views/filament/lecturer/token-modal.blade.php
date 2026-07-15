<div style="display:flex;flex-direction:column;align-items:center;gap:20px;padding:24px 16px;">

    {{-- Token Characters --}}
    <div style="text-align:center;">
        <p style="font-size:12px;color:#6b7280;margin-bottom:12px;text-transform:uppercase;letter-spacing:.05em;">Token Presensi</p>
        <div style="display:flex;gap:8px;justify-content:center;">
            @foreach(str_split($session->attendance_token) as $char)
            <div style="width:48px;height:56px;display:flex;align-items:center;justify-content:center;border-radius:12px;background:#eff6ff;border:2px solid #3b82f6;color:#1d4ed8;font-size:24px;font-weight:700;font-family:monospace;">
                {{ $char }}
            </div>
            @endforeach
        </div>
    </div>

    {{-- Expires --}}
    <div style="text-align:center;background:#fefce8;border:1px solid #fde047;border-radius:12px;padding:12px 24px;">
        <p style="font-size:11px;color:#a16207;margin-bottom:2px;">Berlaku hingga</p>
        <p style="font-size:18px;font-weight:700;color:#ca8a04;">
            {{ $session->token_expires_at?->format('H:i') ?? '—' }}
        </p>
        <p style="font-size:12px;color:#a16207;">
            @if($session->token_expires_at && $session->token_expires_at->isFuture())
            ({{ gmdate('H:i:s', now()->diffInSeconds($session->token_expires_at)) }} lagi)
            @else
            (sudah kedaluwarsa)
            @endif
        </p>
    </div>

    {{-- Session Info --}}
    <div style="width:100%;background:#f9fafb;border:1px solid #e5e7eb;border-radius:12px;padding:16px;display:grid;grid-template-columns:1fr 1fr;gap:12px;">
        <div>
            <p style="font-size:11px;color:#9ca3af;margin-bottom:2px;">Mata Kuliah</p>
            <p style="font-size:13px;font-weight:600;color:#111827;">{{ $session->schedule->courseOffering->course->name ?? '—' }}</p>
        </div>
        <div>
            <p style="font-size:11px;color:#9ca3af;margin-bottom:2px;">Kelas</p>
            <p style="font-size:13px;font-weight:600;color:#111827;">{{ $session->schedule->courseOffering->class_code ?? '—' }}</p>
        </div>
        <div>
            <p style="font-size:11px;color:#9ca3af;margin-bottom:2px;">Pertemuan</p>
            <p style="font-size:13px;font-weight:600;color:#111827;">Ke-{{ $session->meeting_number }}</p>
        </div>
        <div>
            <p style="font-size:11px;color:#9ca3af;margin-bottom:2px;">Ruangan</p>
            <p style="font-size:13px;font-weight:600;color:#111827;">{{ $session->classroom->name ?? $session->schedule->classroom->name ?? '—' }}</p>
        </div>
    </div>

    <p style="font-size:12px;color:#9ca3af;text-align:center;">
        Mahasiswa input kode ini di portal untuk mencatat kehadiran
    </p>

</div>
