<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1a1a1a; }

        .page { padding: 20px 28px; }

        /* Header */
        .header { display: table; width: 100%; border-bottom: 2.5px solid #1e3a5f; padding-bottom: 8px; margin-bottom: 12px; }
        .header-left { display: table-cell; vertical-align: middle; width: 70px; }
        .header-center { display: table-cell; vertical-align: middle; text-align: center; }
        .header-logo { width: 55px; height: 55px; }
        .header-title { font-size: 15px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; color: #1e3a5f; }
        .header-subtitle { font-size: 9px; color: #555; margin-top: 2px; }
        .doc-title { font-size: 13px; font-weight: bold; text-align: center; text-transform: uppercase;
                     letter-spacing: 2px; margin: 10px 0 8px; color: #1e3a5f; }

        /* Info mahasiswa */
        .info-table { width: 100%; margin-bottom: 12px; border-collapse: collapse; }
        .info-table td { padding: 3px 6px; font-size: 11px; }
        .info-table .label { width: 130px; color: #555; }
        .info-table .sep { width: 10px; }
        .info-table .val { font-weight: bold; }

        /* Status badge */
        .badge { display: inline-block; padding: 2px 8px; border-radius: 3px; font-size: 9px; font-weight: bold; }
        .badge-approved { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
        .badge-submitted { background: #fef3c7; color: #92400e; border: 1px solid #fcd34d; }
        .badge-locked { background: #f3f4f6; color: #374151; border: 1px solid #d1d5db; }

        /* Tabel MK */
        .mk-table { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        .mk-table th { background: #1e3a5f; color: #fff; padding: 6px 8px; text-align: left; font-size: 10px; }
        .mk-table th.center { text-align: center; }
        .mk-table td { padding: 5px 8px; border-bottom: 1px solid #e5e7eb; font-size: 11px; vertical-align: middle; }
        .mk-table tr:nth-child(even) td { background: #f8fafc; }
        .mk-table td.center { text-align: center; }
        .mk-table .total-row td { background: #f0f4f8; font-weight: bold; border-top: 2px solid #1e3a5f; }
        .mk-table .total-row td.label { color: #1e3a5f; }

        /* Ttd */
        .sign-section { display: table; width: 100%; margin-top: 18px; }
        .sign-box { display: table-cell; width: 50%; padding: 0 10px; text-align: center; }
        .sign-title { font-size: 10px; color: #555; margin-bottom: 50px; }
        .sign-line { border-top: 1px solid #555; padding-top: 4px; font-size: 10px; font-weight: bold; }
        .sign-sub { font-size: 9px; color: #777; }

        /* Footer */
        .footer { margin-top: 16px; border-top: 1px dashed #ccc; padding-top: 6px;
                  font-size: 9px; color: #888; text-align: center; }
        .watermark { font-size: 9px; color: #aaa; text-align: right; margin-top: 4px; }
    </style>
</head>
<body>
<div class="page">

    {{-- Header --}}
    <div class="header">
        <div class="header-left">
            {{-- Placeholder logo --}}
            <div style="width:55px;height:55px;background:#1e3a5f;border-radius:50%;
                        display:flex;align-items:center;justify-content:center;color:#fff;
                        font-size:18px;font-weight:bold;text-align:center;line-height:55px;">U</div>
        </div>
        <div class="header-center">
            <div class="header-title">Universitas Univercity</div>
            <div class="header-subtitle">Jl. Kampus Raya No. 1 · Telp. (021) 000-0000 · univercity.ac.id</div>
        </div>
    </div>

    <div class="doc-title">Kartu Rencana Studi (KRS)</div>

    {{-- Info Mahasiswa --}}
    <table class="info-table">
        <tr>
            <td class="label">Nama Mahasiswa</td>
            <td class="sep">:</td>
            <td class="val">{{ $enrollment->student->user->full_name ?? $enrollment->student->user->name }}</td>
            <td class="label">Semester</td>
            <td class="sep">:</td>
            <td class="val">{{ $enrollment->semester->name }}</td>
        </tr>
        <tr>
            <td class="label">NIM</td>
            <td class="sep">:</td>
            <td class="val">{{ $enrollment->student->nim }}</td>
            <td class="label">Program Studi</td>
            <td class="sep">:</td>
            <td class="val">{{ $enrollment->student->studyProgram->name ?? '—' }}</td>
        </tr>
        <tr>
            <td class="label">Dosen Wali</td>
            <td class="sep">:</td>
            <td class="val">{{ $enrollment->student->advisor?->user?->full_name ?? '—' }}</td>
            <td class="label">Fakultas</td>
            <td class="sep">:</td>
            <td class="val">{{ $enrollment->student->studyProgram?->faculty?->name ?? '—' }}</td>
        </tr>
        <tr>
            <td class="label">Status KRS</td>
            <td class="sep">:</td>
            <td>
                <span class="badge badge-{{ strtolower($enrollment->status->value) }}">
                    {{ $enrollment->status->value }}
                </span>
            </td>
            <td class="label">Tanggal Disetujui</td>
            <td class="sep">:</td>
            <td class="val">{{ $enrollment->approved_at ? $enrollment->approved_at->format('d M Y H:i') : '—' }}</td>
        </tr>
    </table>

    {{-- Tabel Mata Kuliah --}}
    <table class="mk-table">
        <thead>
            <tr>
                <th style="width:30px;" class="center">No.</th>
                <th style="width:70px;">Kode MK</th>
                <th>Mata Kuliah</th>
                <th style="width:50px;" class="center">SKS</th>
                <th style="width:50px;" class="center">Kelas</th>
                <th style="width:80px;" class="center">Hari</th>
                <th style="width:90px;" class="center">Jam</th>
                <th style="width:70px;" class="center">Ruangan</th>
            </tr>
        </thead>
        <tbody>
            @php $totalSks = 0; @endphp
            @forelse($enrollment->items as $i => $item)
                @php
                    $offering  = $item->courseOffering;
                    $course    = $offering?->course;
                    $schedule  = $offering?->schedules->first();
                    $totalSks += (int) ($course?->total_sks ?? 0);
                @endphp
                <tr>
                    <td class="center">{{ $i + 1 }}</td>
                    <td>{{ $course?->code ?? '—' }}</td>
                    <td>{{ $course?->name ?? '—' }}</td>
                    <td class="center">{{ $course?->total_sks ?? 0 }}</td>
                    <td class="center">{{ $offering?->class_code ?? '—' }}</td>
                    <td class="center">{{ $schedule?->day_of_week?->value ?? '—' }}</td>
                    <td class="center">
                        @if($schedule)
                            {{ $schedule->start_time }} – {{ $schedule->end_time }}
                        @else
                            —
                        @endif
                    </td>
                    <td class="center">{{ $schedule?->classroom?->name ?? '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="center" style="color:#888;padding:12px;">Belum ada mata kuliah.</td>
                </tr>
            @endforelse
            <tr class="total-row">
                <td colspan="3" class="label" style="padding-left:8px;">Total SKS Diambil</td>
                <td class="center">{{ $totalSks }}</td>
                <td colspan="4"></td>
            </tr>
        </tbody>
    </table>

    {{-- Tanda Tangan --}}
    <div class="sign-section">
        <div class="sign-box">
            <div class="sign-title">Mahasiswa</div>
            <div class="sign-line">{{ $enrollment->student->user->full_name ?? $enrollment->student->user->name }}</div>
            <div class="sign-sub">NIM. {{ $enrollment->student->nim }}</div>
        </div>
        <div class="sign-box">
            <div class="sign-title">Dosen Wali</div>
            <div class="sign-line">{{ $enrollment->student->advisor?->user?->full_name ?? '____________________' }}</div>
            <div class="sign-sub">
                {{ $enrollment->student->advisor?->nidn ? 'NIDN. '.$enrollment->student->advisor->nidn : '' }}
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="footer">
        Dokumen ini dicetak secara elektronik dari Sistem Informasi Akademik Univercity.
        Dicetak pada: {{ now()->format('d M Y H:i') }} WIB
    </div>

</div>
</body>
</html>
