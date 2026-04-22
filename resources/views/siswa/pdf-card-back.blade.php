<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Siswa - Belakang</title>
    <style>
        @page { size: A4 portrait; margin: 16mm; }
        html, body { margin: 0; padding: 0; font-family: DejaVu Sans, sans-serif; color: #0f172a; }
        * { box-sizing: border-box; }
        table { border-collapse: collapse; }
        .page { width: 100%; }
        .card-shell { width: 100%; text-align: center; padding-top: 86pt; }
        .card {
            width: 495pt;
            margin: 0 auto;
            border: 1pt solid #e6dcc8;
            border-radius: 20pt;
            overflow: hidden;
            background: #fffdf8;
            page-break-inside: avoid;
        }
        .topbar { height: 9pt; background: #f59e0b; }
        .header { background: #fff7ed; border-bottom: 1pt solid #f3e2bf; }
        .header-cell { padding: 10pt 14pt; }
        .logo-wrap { width: 34pt; height: 34pt; border-radius: 999pt; background: #ffffff; border: 1pt solid #fed7aa; overflow: hidden; text-align: center; }
        .logo-wrap img { width: 24pt; height: 24pt; margin-top: 5pt; object-fit: contain; }
        .logo-fallback { display:block; width:34pt; height:34pt; line-height:34pt; font-size:12pt; font-weight:700; color:#9a3412; }
        .eyebrow { font-size: 8pt; letter-spacing: 2pt; text-transform: uppercase; color: #c2410c; }
        .school { margin-top: 2pt; font-size: 19pt; font-weight: 700; line-height: 1.08; color: #111827; }
        .tagline { margin-top: 2pt; font-size: 9.5pt; line-height: 1.15; color: #78716c; }
        .body-cell { padding: 12pt 16pt 8pt; vertical-align: top; }
        .body-table { table-layout: fixed; }
        .left-blank { width: 88pt; vertical-align: top; }
        .center { width: 305pt; vertical-align: top; text-align: left; padding-right: 18pt; }
        .right-blank { width: 140pt; vertical-align: top; text-align: center; }
        .ghost-photo {
            width: 78pt;
            height: 96pt;
            margin: 0 auto;
        }
        .ghost-qr {
            width: 108pt;
            height: 138pt;
            margin: 0 auto;
        }
        .chip { display: inline-block; padding: 4pt 10pt; border-radius: 999pt; background: #e0e7ff; color: #3730a3; font-size: 8.5pt; letter-spacing: 1.2pt; text-transform: uppercase; }
        .guide-title { margin-top: 14pt; font-size: 16pt; font-weight: 700; line-height: 1.05; color: #0f172a; }
        .guide-sub { margin-top: 7pt; font-size: 8.6pt; font-weight: 600; color: #475569; }
        .rule-list { margin-top: 12pt; padding: 0; list-style: none; }
        .rule-list li { margin-bottom: 5pt; font-size: 7.6pt; line-height: 1.32; color: #334155; }
        .footer { border-top: 1pt solid #e2e8f0; background: #f8fafc; }
        .footer-cell { padding: 6pt 14pt; font-size: 8pt; line-height: 1.25; color: #64748b; text-align: left; }
    </style>
</head>
<body>
<div class="page">
    <div class="card-shell">
        <table class="card" cellspacing="0" cellpadding="0">
            <tr><td class="topbar"></td></tr>
            <tr>
                <td class="header">
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="52" class="header-cell" valign="top">
                                <div class="logo-wrap">
                                    @if($logoPath)<img src="{{ $logoPath }}" alt="Logo sekolah">@else<span class="logo-fallback">{{ $brandInitials }}</span>@endif
                                </div>
                            </td>
                            <td class="header-cell" valign="top" style="text-align:left;">
                                <div class="eyebrow">Sisi Belakang Kartu</div>
                                <div class="school">{{ $sekolah }}</div>
                                <div class="tagline">{{ $tagline }}</div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="body-cell">
                    <table class="body-table" width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="left-blank">
                                <div class="ghost-photo"></div>
                            </td>
                            <td class="center">
                                <span class="chip">Panduan Penggunaan</span>
                                <div class="guide-title">Panduan absensi.</div>
                                <div class="guide-sub">Gunakan sisi depan kartu saat proses scan masuk dan pulang di titik absensi sekolah.</div>
                                <ul class="rule-list">
                                    <li>1. Bawa kartu ke titik absensi.</li>
                                    <li>2. Tunjukkan sisi depan saat masuk dan pulang.</li>
                                    <li>3. Jika rusak atau hilang, hubungi admin.</li>
                                </ul>
                            </td>
                            <td class="right-blank">
                                <div class="ghost-qr"></div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="footer">
                    <div class="footer-cell">Mohon dijaga dengan baik untuk operasional absensi harian.</div>
                </td>
            </tr>
        </table>
    </div>
</div>
</body>
</html>
