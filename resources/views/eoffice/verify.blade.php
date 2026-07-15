<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Surat &mdash; Univercity</title>
    <style>
        body { font-family: -apple-system, 'Segoe UI', sans-serif; background: #f0f4f8; margin: 0;
               display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 16px; }
        .card { background: #fff; border-radius: 10px; box-shadow: 0 2px 12px rgba(0,0,0,.08);
                max-width: 420px; width: 100%; padding: 28px; text-align: center; }
        .icon { font-size: 40px; margin-bottom: 8px; }
        .status-valid { color: #16a34a; }
        .status-invalid { color: #dc2626; }
        .status-expired { color: #d97706; }
        h1 { font-size: 16px; margin: 0 0 16px; color: #1e3a5f; }
        table { width: 100%; text-align: left; margin-top: 12px; font-size: 13px; border-collapse: collapse; }
        td { padding: 6px 4px; border-bottom: 1px solid #eee; }
        td.lbl { color: #667; width: 40%; }
        td.val { font-weight: 600; color: #1a1a1a; }
        .msg { font-size: 13px; color: #555; margin-top: 8px; }
        .footer { margin-top: 20px; font-size: 10px; color: #999; }
    </style>
</head>
<body>
<div class="card">
    @if($state === 'valid')
        <div class="icon status-valid">&#10003;</div>
        <h1>Surat Terverifikasi</h1>
        <table>
            <tr><td class="lbl">Nomor Surat</td><td class="val">{{ $letterRequest->letter_number }}</td></tr>
            <tr><td class="lbl">Jenis Surat</td><td class="val">{{ $letterRequest->template?->name }}</td></tr>
            <tr><td class="lbl">Atas Nama</td><td class="val">{{ $letterRequest->student?->user?->name ?? $letterRequest->requester?->name }}</td></tr>
            <tr><td class="lbl">Diterbitkan</td><td class="val">{{ $letterRequest->issued_at?->format('d M Y') }}</td></tr>
            <tr><td class="lbl">Berlaku Hingga</td><td class="val">{{ $letterRequest->qr_valid_until?->format('d M Y') }}</td></tr>
            <tr><td class="lbl">Status</td><td class="val">{{ $letterRequest->status->value }}</td></tr>
        </table>
    @elseif($state === 'expired')
        <div class="icon status-expired">&#9888;</div>
        <h1>Masa Berlaku Verifikasi Habis</h1>
        <p class="msg">Surat dengan kode ini pernah diterbitkan namun masa berlaku QR verifikasi sudah lewat.</p>
    @else
        <div class="icon status-invalid">&#10007;</div>
        <h1>Kode Tidak Ditemukan</h1>
        <p class="msg">Kode verifikasi tidak valid atau surat belum diterbitkan.</p>
    @endif
    <div class="footer">Sistem Informasi Akademik Univercity</div>
</div>
</body>
</html>
