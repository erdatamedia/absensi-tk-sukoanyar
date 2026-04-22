<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Absensi</title>
    <style>
        @page { margin: 22px 24px; }
        body { font-family: DejaVu Sans, sans-serif; color: #0f172a; font-size: 11px; }
        .header-table, .summary-table, .report-table { width: 100%; border-collapse: collapse; }
        .header-table td { vertical-align: top; }
        .brand-logo { width: 56px; height: 56px; border: 1px solid #fdba74; border-radius: 50%; text-align: center; vertical-align: middle; }
        .brand-logo img { width: 42px; height: 42px; object-fit: contain; margin-top: 6px; }
        .brand-fallback { font-size: 18px; font-weight: bold; color: #9a3412; }
        .eyebrow { text-transform: uppercase; letter-spacing: 0.28em; font-size: 9px; color: #c2410c; }
        .title { font-size: 26px; font-weight: bold; margin-top: 6px; }
        .subtitle { font-size: 12px; color: #475569; margin-top: 4px; }
        .section-title { font-size: 14px; font-weight: bold; margin: 18px 0 10px; }
        .summary-table td { width: 20%; border: 1px solid #cbd5e1; padding: 10px; background: #f8fafc; }
        .summary-label { font-size: 9px; text-transform: uppercase; color: #64748b; }
        .summary-value { font-size: 20px; font-weight: bold; margin-top: 4px; }
        .report-table th { background: #f8fafc; border: 1px solid #cbd5e1; padding: 8px; text-align: left; font-size: 10px; }
        .report-table td { border: 1px solid #e2e8f0; padding: 7px; vertical-align: top; }
        .badge { display: inline-block; padding: 3px 7px; border-radius: 999px; font-size: 9px; font-weight: bold; }
        .badge-hadir { background: #dcfce7; color: #166534; }
        .badge-izin { background: #fef3c7; color: #92400e; }
        .badge-sakit { background: #e0f2fe; color: #075985; }
        .badge-alpha { background: #ffe4e6; color: #9f1239; }
        .badge-belum { background: #e2e8f0; color: #334155; }
        .muted { color: #64748b; }
        .page-break { page-break-before: always; }
    </style>
</head>
<body>
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
                <div class="eyebrow">Rekap {{ $periodMeta['label'] }} Absensi</div>
                <div class="title">{{ $schoolName }}</div>
                <div class="subtitle">{{ $schoolTagline }}</div>
                <div class="subtitle">Periode: {{ $periodMeta['range_label'] }} | Kelas: {{ $selectedKelas?->nama_kelas ?? 'Semua Kelas' }}</div>
                <div class="subtitle">Dibuat: {{ $generatedAt->format('d/m/Y H:i') }} WIB</div>
            </td>
        </tr>
    </table>

    <div class="section-title">Ringkasan Utama</div>
    <table class="summary-table">
        <tr>
            <td><div class="summary-label">Total Siswa</div><div class="summary-value">{{ $summary['total_siswa'] }}</div></td>
            <td><div class="summary-label">Hadir</div><div class="summary-value">{{ $summary['hadir'] }}</div></td>
            <td><div class="summary-label">{{ $periodMeta['is_single_day'] ? 'Belum Masuk' : 'Tanpa Catatan' }}</div><div class="summary-value">{{ $summary['belum_masuk'] }}</div></td>
            <td><div class="summary-label">Sudah Pulang</div><div class="summary-value">{{ $summary['sudah_pulang'] }}</div></td>
            <td><div class="summary-label">Terlambat</div><div class="summary-value">{{ $summary['terlambat'] }}</div></td>
        </tr>
    </table>

    @foreach($classReports as $report)
        <div class="{{ $loop->first ? '' : 'page-break' }}">
            <div class="section-title">{{ $report['kelas']->nama_kelas }}</div>
            <div class="muted" style="margin-bottom: 8px;">
                Total {{ $report['summary']['total_siswa'] }} siswa | Hadir {{ $report['summary']['hadir'] }} | Izin {{ $report['summary']['izin'] }} | Sakit {{ $report['summary']['sakit'] }} | Alpha {{ $report['summary']['alpha'] }} | {{ $periodMeta['is_single_day'] ? 'Belum Masuk' : 'Tanpa Catatan' }} {{ $report['summary']['belum_masuk'] }}
            </div>

            <table class="report-table">
                <thead>
                    <tr>
                        <th style="width: 28px;">No</th>
                        <th style="width: 74px;">NIS</th>
                        <th>Nama</th>
                        <th style="width: 110px;">Status</th>
                        <th style="width: 60px;">{{ $periodMeta['is_single_day'] ? 'Masuk' : 'Hadir' }}</th>
                        <th style="width: 60px;">{{ $periodMeta['is_single_day'] ? 'Pulang' : 'Izin' }}</th>
                        <th style="width: 58px;">{{ $periodMeta['is_single_day'] ? 'Terlambat' : 'Sakit' }}</th>
                        <th style="width: 80px;">{{ $periodMeta['is_single_day'] ? 'Keterangan' : 'Alpha' }}</th>
                        <th style="width: 85px;">{{ $periodMeta['is_single_day'] ? 'Foto' : 'Terakhir' }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($report['rows'] as $row)
                        @php
                            $badgeClass = match($row['status']) {
                                'hadir' => 'badge-hadir',
                                'izin' => 'badge-izin',
                                'sakit' => 'badge-sakit',
                                'alpha' => 'badge-alpha',
                                default => 'badge-belum',
                            };
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $row['nis'] }}</td>
                            <td>{{ $row['nama'] }}</td>
                            <td><span class="badge {{ $badgeClass }}">{{ $row['status_label'] }}</span></td>
                            <td>{{ $periodMeta['is_single_day'] ? ($row['jam_masuk'] ?? '-') : $row['total_hadir'] }}</td>
                            <td>{{ $periodMeta['is_single_day'] ? ($row['jam_pulang'] ?? '-') : $row['total_izin'] }}</td>
                            <td>{{ $periodMeta['is_single_day'] ? ($row['terlambat'] ? 'Ya' : 'Tidak') : $row['total_sakit'] }}</td>
                            <td>{{ $periodMeta['is_single_day'] ? ($row['keterangan'] ?? '-') : $row['total_alpha'] }}</td>
                            <td>{{ $periodMeta['is_single_day'] ? (!empty($row['foto_masuk']) ? 'Ada' : '-') : ($row['last_tanggal'] ?? '-') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach
</body>
</html>
