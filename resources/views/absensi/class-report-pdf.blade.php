<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Template Laporan Kelas</title>
    <style>
        @page { margin: 22px 24px; }
        body { font-family: DejaVu Sans, sans-serif; color: #0f172a; font-size: 11px; }
        .header-table, .meta-table, .report-table { width: 100%; border-collapse: collapse; }
        .header-table td { vertical-align: top; }
        .brand-logo { width: 56px; height: 56px; border: 1px solid #fdba74; border-radius: 50%; text-align: center; vertical-align: middle; }
        .brand-logo img { width: 42px; height: 42px; object-fit: contain; margin-top: 6px; }
        .brand-fallback { font-size: 18px; font-weight: bold; color: #9a3412; }
        .eyebrow { text-transform: uppercase; letter-spacing: 0.28em; font-size: 9px; color: #c2410c; }
        .title { font-size: 26px; font-weight: bold; margin-top: 6px; }
        .subtitle { font-size: 12px; color: #475569; margin-top: 4px; }
        .meta-table td { border: 1px solid #cbd5e1; padding: 8px; background: #f8fafc; }
        .meta-label { font-size: 9px; text-transform: uppercase; color: #64748b; }
        .meta-value { font-size: 16px; font-weight: bold; margin-top: 4px; }
        .section-title { font-size: 15px; font-weight: bold; margin: 18px 0 10px; }
        .report-table th { background: #f8fafc; border: 1px solid #cbd5e1; padding: 8px; text-align: left; font-size: 10px; }
        .report-table td { border: 1px solid #e2e8f0; padding: 7px; vertical-align: top; }
        .page-break { page-break-before: always; }
        .footer-note { margin-top: 16px; font-size: 10px; color: #475569; }
    </style>
</head>
<body>
    @foreach($classReports as $report)
        <div class="{{ $loop->first ? '' : 'page-break' }}">
            <table class="header-table">
                <tr>
                    <td style="width: 72px;">
                        <div class="brand-logo">
                            @if($logoPath)
                                <img src="{{ $logoPath }}" alt="Logo Sekolah">
                            @else
                                <div class="brand-fallback">{{ \App\Support\Branding::initials() }}</div>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="eyebrow">Template Laporan {{ $periodMeta['label'] }}</div>
                        <div class="title">{{ $schoolName }}</div>
                        <div class="subtitle">{{ $schoolTagline }}</div>
                        <div class="subtitle">Laporan untuk {{ $report['kelas']->nama_kelas }} | {{ $periodMeta['range_label'] }}</div>
                    </td>
                </tr>
            </table>

            <div class="section-title">Ringkasan {{ $report['kelas']->nama_kelas }}</div>
            <table class="meta-table">
                <tr>
                    <td><div class="meta-label">Total Siswa</div><div class="meta-value">{{ $report['summary']['total_siswa'] }}</div></td>
                    <td><div class="meta-label">Hadir</div><div class="meta-value">{{ $report['summary']['hadir'] }}</div></td>
                    <td><div class="meta-label">{{ $periodMeta['is_single_day'] ? 'Belum Masuk' : 'Tanpa Catatan' }}</div><div class="meta-value">{{ $report['summary']['belum_masuk'] }}</div></td>
                    <td><div class="meta-label">Sudah Pulang</div><div class="meta-value">{{ $report['summary']['sudah_pulang'] }}</div></td>
                </tr>
            </table>

            <div class="section-title">Daftar Siswa</div>
            <table class="report-table">
                <thead>
                    <tr>
                        <th style="width: 28px;">No</th>
                        <th style="width: 80px;">NIS</th>
                        <th>Nama</th>
                        <th style="width: 110px;">Status</th>
                        <th style="width: 64px;">{{ $periodMeta['is_single_day'] ? 'Masuk' : 'Hadir' }}</th>
                        <th style="width: 64px;">{{ $periodMeta['is_single_day'] ? 'Pulang' : 'Izin' }}</th>
                        <th style="width: 68px;">{{ $periodMeta['is_single_day'] ? 'Terlambat' : 'Sakit' }}</th>
                        <th style="width: 120px;">{{ $periodMeta['is_single_day'] ? 'Catatan' : 'Alpha / Terakhir' }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($report['rows'] as $row)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $row['nis'] }}</td>
                            <td>{{ $row['nama'] }}</td>
                            <td>{{ $row['status_label'] }}</td>
                            <td>{{ $periodMeta['is_single_day'] ? ($row['jam_masuk'] ?? '-') : $row['total_hadir'] }}</td>
                            <td>{{ $periodMeta['is_single_day'] ? ($row['jam_pulang'] ?? '-') : $row['total_izin'] }}</td>
                            <td>{{ $periodMeta['is_single_day'] ? ($row['terlambat'] ? 'Ya' : 'Tidak') : $row['total_sakit'] }}</td>
                            <td>{{ $periodMeta['is_single_day'] ? ($row['keterangan'] ?? '-') : ($row['total_alpha'] . ' / ' . ($row['last_tanggal'] ?? '-')) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="footer-note">
                Template ini disusun untuk pelaporan internal guru kelas maupun pembaruan singkat ke orang tua. Dibuat pada {{ $generatedAt->format('d/m/Y H:i') }} WIB.
            </div>
        </div>
    @endforeach
</body>
</html>
