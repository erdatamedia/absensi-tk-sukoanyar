<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Siswa Massal</title>
    <style>
        @page { margin: 8mm; }
        body { margin: 0; font-family: DejaVu Sans, sans-serif; color: #0f172a; }
        * { box-sizing: border-box; }
        table { border-collapse: collapse; }
        .page-break { page-break-after: always; }
        .title { margin-bottom: 4mm; }
        .title h1 { margin: 0; font-size: 15pt; color: #111827; }
        .title p { margin: 2pt 0 0; font-size: 7.5pt; color: #64748b; }
        .grid { width: 100%; border-collapse: separate; border-spacing: 2.5mm 2.5mm; }
        .slot { width: 50%; vertical-align: top; }
        .card { width: 95mm; height: 62mm; border: 1pt solid #e6dcc8; background: #fffdf8; }
        .topbar { height: 4mm; background: #f59e0b; }
        .header { height: 12mm; background: #fff7ed; border-bottom: 1pt solid #f3e2bf; }
        .header-pad { padding: 2.2mm 3mm; }
        .logo-cell { width: 10mm; }
        .logo-wrap { width: 8mm; height: 8mm; border-radius: 99px; background: #ffffff; border: 1pt solid #fed7aa; text-align: center; overflow: hidden; }
        .logo-wrap img { width: 6mm; height: 6mm; margin-top: 1mm; object-fit: contain; }
        .logo-fallback { display: block; width: 8mm; height: 8mm; line-height: 8mm; font-size: 7pt; font-weight: 700; color: #9a3412; }
        .eyebrow { font-size: 4.3pt; letter-spacing: 0.8pt; text-transform: uppercase; color: #c2410c; }
        .school { margin-top: 0.5mm; font-size: 7.6pt; font-weight: 700; color: #111827; }
        .body { padding: 2.8mm 3mm; }
        .left { width: 61mm; vertical-align: top; }
        .right { width: 24mm; vertical-align: top; text-align: center; }
        .kelas { display: inline-block; padding: 1mm 2.2mm; border-radius: 99px; background: #dbeafe; color: #1d4ed8; font-size: 4.7pt; font-weight: 700; text-transform: uppercase; }
        .student { margin-top: 1.8mm; font-size: 13pt; font-weight: 700; line-height: 1; }
        .meta { margin-top: 2mm; width: 100%; }
        .meta td { padding-bottom: 0.9mm; vertical-align: top; }
        .label { width: 14mm; font-size: 5.2pt; font-weight: 700; letter-spacing: 0.35pt; text-transform: uppercase; color: #64748b; }
        .value { font-size: 6.4pt; font-weight: 600; color: #111827; }
        .qr-shell { display: inline-block; padding: 1.2mm; border: 1pt solid #cbd5e1; border-radius: 2.6mm; background: #ffffff; }
        .qr-shell img { width: 19mm; height: 19mm; display: block; }
        .note { margin-top: 1mm; font-size: 4.2pt; line-height: 1.35; color: #64748b; }
    </style>
</head>
<body>
@foreach($cards as $pageIndex => $pageCards)
    <div class="title">
        <h1>{{ $sekolah }}</h1>
        <p>Cetak kartu siswa • halaman {{ $pageIndex + 1 }} • {{ $showAddress ? 'dengan alamat' : 'tanpa alamat' }}</p>
    </div>

    <table class="grid">
        @foreach($pageCards->chunk(2) as $row)
            <tr>
                @foreach($row as $card)
                    <td class="slot">
                        <table class="card" cellspacing="0" cellpadding="0">
                            <tr><td class="topbar"></td></tr>
                            <tr>
                                <td class="header">
                                    <div class="header-pad">
                                        <table width="100%" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td class="logo-cell" valign="top">
                                                    <div class="logo-wrap">
                                                        @if($logoPath)
                                                            <img src="{{ $logoPath }}" alt="Logo sekolah">
                                                        @else
                                                            <span class="logo-fallback">{{ $brandInitials }}</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td valign="top">
                                                    <div class="eyebrow">Kartu Absensi</div>
                                                    <div class="school">{{ $sekolah }}</div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="body">
                                    <table width="100%" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td class="left">
                                                <span class="kelas">{{ $card['kelas'] }}</span>
                                                <div class="student">{{ $card['nama'] }}</div>
                                                <table class="meta" cellspacing="0" cellpadding="0">
                                                    <tr><td class="label">NIS</td><td class="value">{{ $card['nis'] }}</td></tr>
                                                    <tr><td class="label">Lahir</td><td class="value">{{ $card['tanggal_lahir'] }}</td></tr>
                                                    @if($card['show_address'])<tr><td class="label">Alamat</td><td class="value">{{ $card['alamat'] }}</td></tr>@endif
                                                </table>
                                            </td>
                                            <td class="right">
                                                <div class="qr-shell"><img src="{{ $card['qr_data_uri'] }}" alt="QR siswa"></div>
                                                <div class="note">Scan di titik absensi</div>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                @endforeach
                @if($row->count() === 1)
                    <td class="slot"></td>
                @endif
            </tr>
        @endforeach
    </table>

    @if(! $loop->last)
        <div class="page-break"></div>
    @endif
@endforeach
</body>
</html>
