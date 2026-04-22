<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Siswa Massal - Belakang</title>
    <style>
        @page { size: A4 portrait; margin: 12mm; }
        html, body { margin: 0; padding: 0; font-family: DejaVu Sans, sans-serif; color: #0f172a; }
        * { box-sizing: border-box; }
        table { border-collapse: collapse; }
        .page { page-break-after: always; }
        .page:last-child { page-break-after: auto; }
        .grid { width: 100%; }
        .cell { width: 50%; padding: 8pt; vertical-align: top; }
        .card {
            width: 100%;
            border-radius: 18pt;
            overflow: hidden;
            background: #fffdf8;
            border: 1pt solid #e6dcc8;
        }
        .topbar { height: 8pt; background: #f59e0b; }
        .inner { padding: 14pt; }
        .chip { display: inline-block; padding: 3pt 8pt; border-radius: 999pt; background: #e0e7ff; color: #3730a3; font-size: 7pt; letter-spacing: 1pt; text-transform: uppercase; }
        .brand { margin-top: 10pt; }
        .logo-wrap { width: 28pt; height: 28pt; border-radius: 999pt; background: #fff; border: 1pt solid #fed7aa; overflow: hidden; text-align: center; }
        .logo-wrap img { width: 20pt; height: 20pt; margin-top: 4pt; object-fit: contain; }
        .logo-fallback { display:block; width:28pt; height:28pt; line-height:28pt; font-size:10pt; font-weight:700; color:#9a3412; }
        .school { font-size: 11pt; font-weight: 700; line-height: 1.08; color: #111827; }
        .tagline { margin-top: 1pt; font-size: 6.8pt; color: #64748b; }
        .content { margin-top: 12pt; }
        .left { width: 58%; vertical-align: top; text-align: left; padding-right: 10pt; }
        .right { width: 42%; vertical-align: top; text-align: center; }
        .student { font-size: 16pt; font-weight: 700; line-height: 1.05; color: #0f172a; }
        .sub { margin-top: 2pt; font-size: 7.5pt; color: #475569; }
        .rule-list { margin: 10pt 0 0; padding: 0; list-style: none; }
        .rule-list li { margin-bottom: 5pt; font-size: 7pt; line-height: 1.45; color: #334155; }
        .qr-shell { display: inline-block; padding: 5pt; border-radius: 12pt; background: #fff; border: 1pt solid #cbd5e1; }
        .qr-shell img { width: 74pt; height: 74pt; display: block; }
        .token { margin-top: 7pt; font-size: 6.5pt; color: #475569; line-height: 1.4; }
    </style>
</head>
<body>
@foreach($cards as $pageCards)
    <div class="page">
        <table class="grid" cellspacing="0" cellpadding="0">
            @foreach($pageCards->chunk(2) as $row)
                <tr>
                    @foreach($row as $siswa)
                        <td class="cell">
                            <table class="card" cellspacing="0" cellpadding="0">
                                <tr><td class="topbar"></td></tr>
                                <tr>
                                    <td class="inner">
                                        <div class="chip">Sisi Belakang</div>
                                        <table class="brand" width="100%" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td width="38" valign="top">
                                                    <div class="logo-wrap">
                                                        @if($logoPath)<img src="{{ $logoPath }}" alt="Logo sekolah">@else<span class="logo-fallback">{{ $brandInitials }}</span>@endif
                                                    </div>
                                                </td>
                                                <td valign="top" style="text-align:left;">
                                                    <div class="school">{{ $sekolah }}</div>
                                                    <div class="tagline">{{ $tagline }}</div>
                                                </td>
                                            </tr>
                                        </table>
                                        <table class="content" width="100%" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td class="left">
                                                    <div class="student">{{ $siswa['nama'] }}</div>
                                                    <div class="sub">{{ $siswa['kelas'] }} • NIS {{ $siswa['nis'] }}</div>
                                                    <ul class="rule-list">
                                                        <li>1. Bawa kartu ini ke titik absensi.</li>
                                                        <li>2. Tunjukkan QR saat masuk dan pulang.</li>
                                                        <li>3. Hubungi admin untuk cetak ulang jika hilang.</li>
                                                    </ul>
                                                </td>
                                                <td class="right">
                                                    <div class="qr-shell"><img src="{{ $siswa['qr_data_uri'] }}" alt="QR siswa"></div>
                                                    <div class="token">{{ $siswa['token_preview'] }}</div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    @endforeach
                    @if($row->count() < 2)
                        <td class="cell"></td>
                    @endif
                </tr>
            @endforeach
        </table>
    </div>
@endforeach
</body>
</html>
