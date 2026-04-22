<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Siswa - Depan</title>
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
        .logo-wrap {
            width: 34pt; height: 34pt; border-radius: 999pt; background: #ffffff;
            border: 1pt solid #fed7aa; overflow: hidden; text-align: center;
        }
        .logo-wrap img { width: 24pt; height: 24pt; margin-top: 5pt; object-fit: contain; }
        .logo-fallback { display: block; width: 34pt; height: 34pt; line-height: 34pt; font-size: 12pt; font-weight: 700; color: #9a3412; }
        .eyebrow { font-size: 8pt; letter-spacing: 2pt; text-transform: uppercase; color: #c2410c; }
        .school { margin-top: 2pt; font-size: 19pt; font-weight: 700; line-height: 1.08; color: #111827; }
        .tagline { margin-top: 2pt; font-size: 9.5pt; line-height: 1.15; color: #78716c; }
        .body-cell { padding: 12pt 16pt 8pt; vertical-align: top; }
        .left { width: 305pt; padding-right: 18pt; vertical-align: top; text-align: left; }
        .right { width: 140pt; vertical-align: top; text-align: center; }
        .profile-box {
            width: 78pt; height: 96pt; border: 1pt dashed #cbd5e1; border-radius: 14pt;
            background: linear-gradient(180deg, #f8fafc 0%, #eef2ff 100%); text-align: center;
        }
        .profile-icon {
            width: 34pt; height: 34pt; margin: 17pt auto 0; border-radius: 999pt; background: #dbeafe;
            border: 1pt solid #bfdbfe;
        }
        .profile-line {
            width: 46pt; height: 6pt; margin: 10pt auto 0; border-radius: 999pt; background: #cbd5e1;
        }
        .profile-caption {
            margin-top: 10pt; font-size: 6.5pt; letter-spacing: 1pt; text-transform: uppercase; color: #64748b;
        }
        .info-box { vertical-align: top; padding-left: 10pt; }
        .kelas { display: inline-block; padding: 4pt 10pt; border-radius: 999pt; background: #dbeafe; color: #1d4ed8; font-size: 8.5pt; font-weight: 700; text-transform: uppercase; }
        .student { margin-top: 14pt; font-size: 28pt; font-weight: 700; line-height: 1; color: #0f172a; }
        .meta { margin-top: 12pt; width: 100%; }
        .meta td { padding-bottom: 5pt; vertical-align: top; text-align: left; }
        .label { width: 78pt; font-size: 8.6pt; font-weight: 700; text-transform: uppercase; letter-spacing: 1.2pt; color: #64748b; }
        .value { font-size: 12.5pt; font-weight: 600; color: #111827; }
        .qr-shell { display: inline-block; padding: 6pt; border: 1pt solid #cbd5e1; border-radius: 14pt; background: #ffffff; }
        .qr-shell img { width: 96pt; height: 96pt; display: block; }
        .scan-note { margin-top: 8pt; font-size: 8.8pt; line-height: 1.45; color: #475569; }
        .footer { border-top: 1pt solid #e2e8f0; background: #f8fafc; }
        .footer-cell { padding: 7pt 14pt; font-size: 8pt; line-height: 1.3; color: #64748b; text-align: left; }
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
                                <div class="eyebrow">Kartu Absensi Siswa</div>
                                <div class="school">{{ $sekolah }}</div>
                                <div class="tagline">{{ $tagline }}</div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="body-cell">
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="left">
                                <table width="100%" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="88" valign="top">
                                            <div class="profile-box">
                                                <div class="profile-icon"></div>
                                                <div class="profile-line"></div>
                                                <div class="profile-caption">Foto Siswa</div>
                                            </div>
                                        </td>
                                        <td class="info-box">
                                            <span class="kelas">{{ $siswa['kelas'] }}</span>
                                            <div class="student">{{ $siswa['nama'] }}</div>
                                            <table class="meta" cellspacing="0" cellpadding="0">
                                                <tr><td class="label">NIS</td><td class="value">{{ $siswa['nis'] }}</td></tr>
                                                <tr><td class="label">Lahir</td><td class="value">{{ $siswa['tanggal_lahir'] }}</td></tr>
                                                <tr><td class="label">Gender</td><td class="value">{{ $siswa['jenis_kelamin'] }}</td></tr>
                                                @if($siswa['show_address'])<tr><td class="label">Alamat</td><td class="value">{{ $siswa['alamat'] }}</td></tr>@endif
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td class="right">
                                <div class="qr-shell"><img src="{{ $siswa['qr_data_uri'] }}" alt="QR siswa"></div>
                                <div class="scan-note">Scan QR ini di titik absensi masuk atau pulang.</div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr><td class="footer"><div class="footer-cell">Kartu operasional absensi harian.@if(! $siswa['show_address']) Alamat disembunyikan. @endif</div></td></tr>
        </table>
    </div>
</div>
</body>
</html>
