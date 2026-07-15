<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #1a1a1a; }
        .page { padding: 18px 26px; }

        /* Header */
        .header { display: table; width: 100%; border-bottom: 2.5px solid #1e3a5f; padding-bottom: 8px; margin-bottom: 10px; }
        .header-left  { display: table-cell; vertical-align: middle; width: 65px; }
        .header-center { display: table-cell; vertical-align: middle; text-align: center; }
        .logo-circle { width: 52px; height: 52px; background: #1e3a5f; border-radius: 50%;
                       color: #fff; font-size: 20px; font-weight: bold; text-align: center; line-height: 52px; }
        .header-title    { font-size: 14px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; color: #1e3a5f; }
        .header-subtitle { font-size: 9px; color: #555; margin-top: 2px; }
        .doc-title { font-size: 13px; font-weight: bold; text-align: center; text-transform: uppercase;
                     letter-spacing: 2px; margin: 8px 0 6px; color: #1e3a5f; }

        /* Info mahasiswa */
        .info-table { width: 100%; margin-bottom: 10px; border-collapse: collapse; }
        .info-table td { padding: 2px 5px; font-size: 10px; }
        .info-table .lbl { width: 120px; color: #555; }
        .info-table .sep { width: 10px; }
        .info-table .val { font-weight: bold; }

        /* Per-semester block */
        .semester-block { margin-bottom: 10px; }
        .semester-head { background: #1e3a5f; color: #fff; padding: 4px 8px; font-size: 10px;
                         font-weight: bold; border-radius: 2px 2px 0 0; }

        .mk-table { width: 100%; border-collapse: collapse; }
        .mk-table th { background: #dbe6f4; color: #1e3a5f; padding: 4px 6px; font-size: 9px; text-align: left; }
        .mk-table th.c { text-align: center; }
        .mk-table td { padding: 4px 6px; border-bottom: 1px solid #eee; font-size: 10px; vertical-align: middle; }
        .mk-table td.c { text-align: center; }
        .mk-table tr:nth-child(even) td { background: #f8fafc; }

        /* Semester summary row */
        .sem-summary { background: #f0f4f8; }
        .sem-summary td { padding: 4px 6px; font-size: 9px; font-weight: bold; color: #1e3a5f;
                          border-top: 1.5px solid #1e3a5f; }

        /* IPK summary */
        .ipk-box { margin-top: 10px; border: 1.5px solid #1e3a5f; border-radius: 3px; padding: 8px 12px; }
        .ipk-box table { width: 100%; border-collapse: collapse; }
        .ipk-box td { padding: 2px 8px; font-size: 11px; }
        .ipk-box .lbl { color: #555; width: 160px; }
        .ipk-box .val { font-weight: bold; font-size: 12px; color: #1e3a5f; }

        /* Tanda tangan */
        .sign-section { display: table; width: 100%; margin-top: 16px; }
        .sign-box { display: table-cell; width: 50%; padding: 0 10px; text-align: center; }
        .sign-title { font-size: 9px; color: #555; margin-bottom: 44px; }
        .sign-line  { border-top: 1px solid #555; padding-top: 3px; font-size: 10px; font-weight: bold; }
        .sign-sub   { font-size: 9px; color: #777; }

        /* Footer */
        .footer { margin-top: 14px; border-top: 1px dashed #ccc; padding-top: 5px;
                  font-size: 9px; color: #888; text-align: center; }

        .grade-e  { color: #dc2626; font-weight: bold; }
        .grade-ok { color: #16a34a; font-weight: bold; }
    </style>
</head>
<body>
<div class="page">

    {{-- Header --}}
    <div class="header">
        <div class="header-left">
            <div class="logo-circle">U</div>
        </div>
        <div class="header-center">
            <div class="header-title">Universitas Univercity</div>
            <div class="header-subtitle">Jl. Kampus Raya No. 1 · Telp. (021) 000-0000 · univercity.ac.id</div>
        </div>
    </div>

    <div class="doc-title">Transkrip Akademik</div>

    {{-- Info mahasiswa --}}
    <table class="info-table">
        <tr>
            <td class="lbl">Nama Mahasiswa</td><td class="sep">:</td>
            <td class="val">{{ $student->user->full_name ?? $student->user->name }}</td>
            <td class="lbl">Program Studi</td><td class="sep">:</td>
            <td class="val">{{ $student->studyProgram->name ?? '—' }}</td>
        </tr>
        <tr>
            <td class="lbl">NIM</td><td class="sep">:</td>
            <td class="val">{{ $student->nim }}</td>
            <td class="lbl">Fakultas</td><td class="sep">:</td>
            <td class="val">{{ $student->studyProgram?->faculty?->name ?? '—' }}</td>
        </tr>
        <tr>
            <td class="lbl">Dosen Wali</td><td class="sep">:</td>
            <td class="val">{{ $student->advisor?->user?->full_name ?? '—' }}</td>
            <td class="lbl">Dicetak</td><td class="sep">:</td>
            <td class="val">{{ now()->format('d M Y H:i') }}</td>
        </tr>
    </table>

    {{-- Per-semester --}}
    @php $grandSksAttempted = 0; $grandSksAcquired = 0; $latestIpk = null; @endphp

    @foreach($semesterData as $data)
        @php
            $enrollment  = $data['enrollment'];
            $transcript  = $data['transcript'];
            $grandSksAttempted += $transcript?->sks_attempted ?? 0;
            $grandSksAcquired  += $transcript?->sks_acquired  ?? 0;
            if ($transcript) $latestIpk = $transcript->cumulative_gpa;
        @endphp
        <div class="semester-block">
            <div class="semester-head">
                {{ $enrollment->semester->name }}
                &nbsp;·&nbsp; SKS: {{ $enrollment->total_sks_taken }}
                @if($transcript)
                    &nbsp;·&nbsp; IPS: {{ number_format((float) $transcript->semester_gpa, 2) }}
                    &nbsp;·&nbsp; IPK: {{ number_format((float) $transcript->cumulative_gpa, 2) }}
                @endif
            </div>

            <table class="mk-table">
                <thead>
                    <tr>
                        <th style="width:25px;" class="c">No</th>
                        <th style="width:65px;">Kode MK</th>
                        <th>Mata Kuliah</th>
                        <th style="width:35px;" class="c">SKS</th>
                        <th style="width:40px;" class="c">Kelas</th>
                        <th style="width:50px;" class="c">Nilai</th>
                        <th style="width:40px;" class="c">Bobot</th>
                        <th style="width:45px;" class="c">Mutu</th>
                    </tr>
                </thead>
                <tbody>
                    @php $semSks = 0; $semMutu = 0; @endphp
                    @forelse($data['items'] as $i => $item)
                        @php
                            $course  = $item['course'];
                            $grade   = $item['grade'];
                            $sks     = (int) ($course?->total_sks ?? 0);
                            $gp      = $grade?->grade_point ? (float) $grade->grade_point : null;
                            $semSks  += $sks;
                            $semMutu += $gp ? $sks * $gp : 0;
                            $gradeClass = $grade?->letter_grade === 'E' ? 'grade-e' : 'grade-ok';
                        @endphp
                        <tr>
                            <td class="c">{{ $i + 1 }}</td>
                            <td>{{ $course?->code ?? '—' }}</td>
                            <td>{{ $course?->name ?? '—' }}</td>
                            <td class="c">{{ $sks }}</td>
                            <td class="c">{{ $item['class_code'] }}</td>
                            <td class="c">
                                @if($grade?->letter_grade)
                                    <span class="{{ $gradeClass }}">{{ $grade->letter_grade }}</span>
                                @else
                                    <span style="color:#aaa;">—</span>
                                @endif
                            </td>
                            <td class="c">{{ $gp ? number_format($gp, 2) : '—' }}</td>
                            <td class="c">{{ ($gp && $sks) ? number_format($sks * $gp, 2) : '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="c" style="color:#aaa;padding:8px;">Tidak ada MK.</td></tr>
                    @endforelse

                    {{-- Summary row --}}
                    <tr class="sem-summary">
                        <td colspan="3" style="padding-left:8px;">Jumlah</td>
                        <td class="c">{{ $semSks }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="c">{{ $semMutu > 0 ? number_format($semMutu, 2) : '—' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endforeach

    {{-- IPK Box --}}
    <div class="ipk-box">
        <table>
            <tr>
                <td class="lbl">Total SKS Ditempuh</td>
                <td class="val">{{ $grandSksAttempted ?: $semesterData->sum(fn($d) => $d['enrollment']->total_sks_taken) }} SKS</td>
                <td class="lbl">IPK Terakhir</td>
                <td class="val">{{ $latestIpk ? number_format((float) $latestIpk, 2) : '—' }}</td>
            </tr>
            <tr>
                <td class="lbl">Total SKS Lulus</td>
                <td class="val">{{ $grandSksAcquired }} SKS</td>
                <td class="lbl">Status Akademik</td>
                <td class="val">{{ $lastAcademicStatus ?? '—' }}</td>
            </tr>
        </table>
    </div>

    {{-- Tanda Tangan --}}
    <div class="sign-section">
        <div class="sign-box">
            <div class="sign-title">Mahasiswa</div>
            <div class="sign-line">{{ $student->user->full_name ?? $student->user->name }}</div>
            <div class="sign-sub">NIM. {{ $student->nim }}</div>
        </div>
        <div class="sign-box">
            <div class="sign-title">Dosen Wali</div>
            <div class="sign-line">{{ $student->advisor?->user?->full_name ?? '____________________' }}</div>
            <div class="sign-sub">{{ $student->advisor?->nidn ? 'NIDN. '.$student->advisor->nidn : '' }}</div>
        </div>
    </div>

    <div class="footer">
        Dokumen ini dicetak secara elektronik dari Sistem Informasi Akademik Univercity.
        Dicetak pada: {{ now()->format('d M Y H:i') }} WIB
    </div>

</div>
</body>
</html>
