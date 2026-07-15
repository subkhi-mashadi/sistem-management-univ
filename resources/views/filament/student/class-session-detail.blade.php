<div style="padding:8px 0;">

    {{-- Header info --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:20px;">
        <div style="background:#f9fafb;border-radius:10px;padding:12px;">
            <p style="font-size:11px;color:#9ca3af;margin:0 0 4px;">Tanggal</p>
            <p style="font-size:14px;font-weight:600;color:#111827;margin:0;">{{ $session->session_date?->format('d M Y') ?? '—' }}</p>
        </div>
        <div style="background:#f9fafb;border-radius:10px;padding:12px;">
            <p style="font-size:11px;color:#9ca3af;margin:0 0 4px;">Pertemuan</p>
            <p style="font-size:14px;font-weight:600;color:#111827;margin:0;">Ke-{{ $session->meeting_number }}</p>
        </div>
        <div style="background:#f9fafb;border-radius:10px;padding:12px;">
            <p style="font-size:11px;color:#9ca3af;margin:0 0 4px;">Jam</p>
            <p style="font-size:14px;font-weight:600;color:#111827;margin:0;">
                {{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}
            </p>
        </div>
        <div style="background:#f9fafb;border-radius:10px;padding:12px;">
            <p style="font-size:11px;color:#9ca3af;margin:0 0 4px;">Ruangan</p>
            <p style="font-size:14px;font-weight:600;color:#111827;margin:0;">
                {{ $session->classroom?->name ?? $session->schedule?->classroom?->name ?? '—' }}
            </p>
        </div>
    </div>

    {{-- Ringkasan Materi --}}
    @if($session->material_summary)
    <div style="margin-bottom:20px;">
        <p style="font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;margin:0 0 8px;">Ringkasan Materi</p>
        <div style="background:#f0f9ff;border-left:3px solid #3b82f6;border-radius:0 8px 8px 0;padding:12px 16px;">
            <p style="font-size:14px;color:#1e40af;margin:0;white-space:pre-line;">{{ $session->material_summary }}</p>
        </div>
    </div>
    @endif

    {{-- Catatan Dosen --}}
    @if($session->notes)
    <div style="margin-bottom:20px;">
        <p style="font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;margin:0 0 8px;">Catatan Dosen</p>
        <div style="background:#fefce8;border-left:3px solid #f59e0b;border-radius:0 8px 8px 0;padding:12px 16px;">
            <p style="font-size:14px;color:#92400e;margin:0;white-space:pre-line;">{{ $session->notes }}</p>
        </div>
    </div>
    @endif

    {{-- File Materi --}}
    @php $files = $session->material_files ?? []; @endphp
    @if(count($files) > 0)
    <div>
        <p style="font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;margin:0 0 8px;">File Materi ({{ count($files) }})</p>
        <div style="display:flex;flex-direction:column;gap:8px;">
            @foreach($files as $file)
            @php
                $filename = basename($file);
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                $iconColor = match($ext) {
                    'pdf'  => '#ef4444',
                    'ppt', 'pptx' => '#f97316',
                    'doc', 'docx' => '#3b82f6',
                    default => '#6b7280',
                };
                $url = \Illuminate\Support\Facades\Storage::url($file);
            @endphp
            <a href="{{ $url }}" target="_blank" style="display:flex;align-items:center;gap:12px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:10px;padding:12px 16px;text-decoration:none;transition:background .15s;">
                <div style="width:36px;height:36px;border-radius:8px;background:{{ $iconColor }}20;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <span style="font-size:11px;font-weight:700;color:{{ $iconColor }};text-transform:uppercase;">{{ $ext }}</span>
                </div>
                <div style="flex:1;min-width:0;">
                    <p style="font-size:13px;font-weight:500;color:#111827;margin:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $filename }}</p>
                    <p style="font-size:11px;color:#9ca3af;margin:0;">Klik untuk buka / unduh</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#9ca3af" style="width:16px;height:16px;flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
            </a>
            @endforeach
        </div>
    </div>
    @else
    <div style="text-align:center;padding:20px;color:#9ca3af;">
        <p style="font-size:14px;margin:0;">Tidak ada file materi untuk sesi ini</p>
    </div>
    @endif

</div>
