<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 12px; color: #1a1a1a; line-height: 1.5; }
        .page { padding: 26px 34px; }

        .header { display: table; width: 100%; border-bottom: 3px solid #1e3a5f; padding-bottom: 10px; margin-bottom: 14px; }
        .header-left { display: table-cell; vertical-align: middle; width: 65px; }
        .header-center { display: table-cell; vertical-align: middle; text-align: center; }
        .logo-circle { width: 54px; height: 54px; background: #1e3a5f; border-radius: 50%;
                       color: #fff; font-size: 20px; font-weight: bold; text-align: center; line-height: 54px; }
        .header-title { font-size: 15px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; color: #1e3a5f; }
        .header-subtitle { font-size: 9px; color: #555; margin-top: 2px; }
        .header-sub2 { font-size: 8px; color: #777; }

        .doc-title { font-size: 13px; font-weight: bold; text-align: center; text-transform: uppercase;
                     letter-spacing: 2px; margin: 14px 0 2px; color: #1e3a5f; text-decoration: underline; }
        .doc-number { text-align: center; font-size: 10px; color: #555; margin-bottom: 18px; }

        .meta-table { width: 100%; margin-bottom: 14px; font-size: 12px; }
        .meta-table td { padding: 1px 4px; vertical-align: top; }
        .meta-table .lbl { width: 90px; }
        .meta-table .sep { width: 10px; }

        .body-content { font-size: 12px; line-height: 1.7; text-align: justify; margin-bottom: 8px; }
        .body-content p { margin-bottom: 10px; }

        .data-table { width: 100%; margin: 10px 0 14px; font-size: 12px; border-collapse: collapse; }
        .data-table td { padding: 3px 6px; vertical-align: top; }
        .data-table .lbl { width: 160px; color: #333; }
        .data-table .sep { width: 12px; }
        .data-table .val { font-weight: bold; }

        .closing { font-size: 12px; line-height: 1.7; text-align: justify; margin-bottom: 6px; }

        .sign-section { display: table; width: 100%; margin-top: 26px; }
        .sign-box-left { display: table-cell; width: 55%; vertical-align: bottom; }
        .sign-box-right { display: table-cell; width: 45%; text-align: center; vertical-align: top; }
        .place-date { font-size: 12px; margin-bottom: 4px; }
        .sign-title { font-size: 12px; margin-bottom: 6px; }
        .sign-name { font-size: 12px; font-weight: bold; text-decoration: underline; }
        .sign-sub { font-size: 10px; color: #444; margin-top: 2px; }

        .qr-box { text-align: center; }
        .qr-box img { width: 88px; height: 88px; }
        .qr-caption { font-size: 8px; color: #777; margin-top: 3px; }

        .footer { margin-top: 20px; border-top: 1px dashed #ccc; padding-top: 6px;
                  font-size: 8px; color: #888; text-align: center; }
    </style>
</head>
<body>
<div class="page">

    <div class="header">
        <div class="header-left">
            <div class="logo-circle">U</div>
        </div>
        <div class="header-center">
            <div class="header-title">Universitas Univercity</div>
            <div class="header-subtitle">Jl. Kampus Raya No. 1, Kota Kampus, Indonesia 12345</div>
            <div class="header-sub2">Telp. (021) 000-0000 &middot; Faks. (021) 000-0001 &middot; www.univercity.ac.id &middot; info@univercity.ac.id</div>
        </div>
    </div>

    <div class="doc-title">{{ $template->name }}</div>
    <div class="doc-number">Nomor: {{ $request->letter_number }}</div>

    <table class="meta-table">
        <tr>
            <td class="lbl">Perihal</td><td class="sep">:</td><td>{{ $template->name }}</td>
        </tr>
    </table>

    <div class="body-content">{!! nl2br(e($introText)) !!}</div>

    <table class="data-table">
        @foreach(($request->form_data ?? []) as $key => $value)
            @continue(is_array($value))
            <tr>
                <td class="lbl">{{ $dataLabels[$key] ?? \Illuminate\Support\Str::of($key)->replace('_', ' ')->title() }}</td>
                <td class="sep">:</td>
                <td class="val">{{ $value }}</td>
            </tr>
        @endforeach
    </table>

    @if($closingText)
        <div class="body-content">{!! nl2br(e($closingText)) !!}</div>
    @endif

    <div class="closing">
        <p>
            Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan
            sebagaimana mestinya. Surat ini bersifat resmi dan sah secara elektronik tanpa
            memerlukan tanda tangan basah maupun cap basah, serta dapat diverifikasi keasliannya
            melalui kode QR yang tertera di bawah ini.
        </p>
    </div>

    <div class="sign-section">
        <div class="sign-box-left">
            <div class="qr-box" style="text-align:left;">
                <img src="data:image/png;base64,{{ $qrBase64 }}" alt="QR Verifikasi">
                <div class="qr-caption" style="text-align:left;">
                    Pindai kode QR untuk verifikasi keaslian surat<br>
                    Berlaku hingga {{ $qrValidUntil->format('d F Y') }}
                </div>
            </div>
        </div>
        <div class="sign-box-right">
            <div class="place-date">Kota Kampus, {{ $issuedAt->format('d F Y') }}</div>
            <div class="sign-title">{{ $signerTitle }}</div>
            @if($signatureImageBase64)
                <img src="{{ $signatureImageBase64 }}" alt="Tanda Tangan" style="display:block;margin:0 auto 2px auto;height:50px;">
            @else
                <div style="height:50px;"></div>
            @endif
            <div class="sign-name">{{ $signerName }}</div>
            <div class="sign-sub">Dokumen elektronik &mdash; sah tanpa tanda tangan basah</div>
        </div>
    </div>

    <div class="footer">
        Dokumen ini diterbitkan secara elektronik oleh Sistem Informasi Akademik Universitas Univercity
        dan sah tanpa tanda tangan basah maupun cap basah.
    </div>

</div>
</body>
</html>
