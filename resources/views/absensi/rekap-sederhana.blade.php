<x-app-layout>
@php
    $operasionalMulai = \App\Support\Branding::operationalStart();
    $operasionalSelesai = \App\Support\Branding::operationalEnd();
    $jamSekarang = now()->format('H:i');
    $selectedKelas = $kelasList->firstWhere('id', $kelasId);
    $filterParams = [
        'period' => $periodMeta['period'],
        'preset' => $periodMeta['preset'],
        'tanggal' => $periodMeta['anchor_date'],
        'tanggal_mulai' => $periodMeta['start_input'],
        'tanggal_selesai' => $periodMeta['end_input'],
        'kelas_id' => $kelasId,
    ];
    $isSingleDay = $periodMeta['is_single_day'];
    $trendPointCount = max($trend['points']->count(), 1);
    $kpiItems = [
        [
            'label' => $isSingleDay ? 'Siswa Hadir Hari Ini' : 'Total Kehadiran',
            'value' => $summary['hadir'],
            'tone' => 'emerald',
        ],
        [
            'label' => $isSingleDay ? 'Siswa Belum Masuk' : 'Siswa Tanpa Catatan',
            'value' => $summary['belum_masuk'],
            'tone' => 'slate',
        ],
        [
            'label' => $isSingleDay ? 'Siswa Sudah Pulang' : 'Total Pulang Tercatat',
            'value' => $summary['sudah_pulang'],
            'tone' => 'sky',
        ],
        [
            'label' => $isSingleDay ? 'Siswa Belum Pulang' : 'Selisih Belum Pulang',
            'value' => $summary['belum_pulang'],
            'tone' => 'amber',
        ],
        [
            'label' => $isSingleDay ? 'Siswa Terlambat' : 'Total Keterlambatan',
            'value' => $summary['terlambat'],
            'tone' => 'rose',
        ],
        [
            'label' => $isSingleDay ? 'Siswa Izin' : 'Total Izin',
            'value' => $summary['izin'],
            'tone' => 'orange',
        ],
        [
            'label' => $isSingleDay ? 'Siswa Sakit' : 'Total Sakit',
            'value' => $summary['sakit'],
            'tone' => 'cyan',
        ],
        [
            'label' => $isSingleDay ? 'Siswa Alpha' : 'Total Alpha',
            'value' => $summary['alpha'],
            'tone' => 'pink',
        ],
        [
            'label' => $isSingleDay ? 'Siswa Dengan Foto Bukti' : 'Total Foto Bukti Tersimpan',
            'value' => $summary['foto_bukti'],
            'tone' => 'violet',
        ],
    ];

    $toneClasses = [
        'emerald' => 'bg-emerald-50 text-emerald-700 ring-emerald-100',
        'slate' => 'bg-slate-100 text-slate-700 ring-slate-200',
        'sky' => 'bg-sky-50 text-sky-700 ring-sky-100',
        'amber' => 'bg-amber-50 text-amber-700 ring-amber-100',
        'rose' => 'bg-rose-50 text-rose-700 ring-rose-100',
        'orange' => 'bg-orange-50 text-orange-700 ring-orange-100',
        'cyan' => 'bg-cyan-50 text-cyan-700 ring-cyan-100',
        'pink' => 'bg-pink-50 text-pink-700 ring-pink-100',
        'violet' => 'bg-violet-50 text-violet-700 ring-violet-100',
    ];
@endphp

    <x-slot name="header">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Laporan Absensi</p>
                <h2 class="mt-1 text-2xl font-semibold leading-tight text-slate-900">{{ $periodMeta['label'] }}</h2>
                <p class="mt-1 text-sm text-slate-500">{{ $periodMeta['range_label'] }} · {{ $selectedKelas?->nama_kelas ?? 'Semua Kelas' }} · {{ $summary['total_siswa'] }} siswa.</p>
            </div>
            <div class="inline-flex items-center rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
                Jam Operasional {{ $operasionalMulai }} - {{ $operasionalSelesai }}:
                <span class="ml-2 font-semibold text-slate-900">{{ $jamSekarang }} WIB</span>
            </div>
        </div>
    </x-slot>

<div class="space-y-6 px-4 py-6 sm:px-6 lg:px-8">
    <section class="rounded-[28px] bg-slate-900 px-6 py-6 text-white shadow-xl shadow-slate-950/10 md:px-8">
        <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
            <div class="space-y-3">
                <span class="inline-flex w-fit items-center rounded-full border border-white/10 bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.28em] text-slate-200">
                    Laporan {{ $periodMeta['label'] }}
                </span>
                <div class="space-y-1">
                    <h1 class="text-3xl font-semibold tracking-tight md:text-4xl">Laporan Absensi</h1>
                    <p class="text-sm font-medium text-slate-200 md:text-base">{{ $periodMeta['label'] }} · {{ $periodMeta['range_label'] }}</p>
                    <p class="text-sm text-slate-300 md:text-base">{{ $selectedKelas?->nama_kelas ?? 'Semua Kelas' }} · {{ $summary['total_siswa'] }} siswa.</p>
                </div>
            </div>
            <div class="grid w-full gap-3 sm:grid-cols-2 md:max-w-md">
                <div class="rounded-2xl border border-white/10 bg-white/10 px-4 py-3">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-300">Total Siswa</p>
                    <p class="mt-2 text-2xl font-semibold text-white">{{ $summary['total_siswa'] }}</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/10 px-4 py-3">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-300">Siswa Tercatat</p>
                    <p class="mt-2 text-2xl font-semibold text-white">{{ $summary['students_with_records'] }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="grid gap-6 xl:grid-cols-[minmax(0,1.1fr)_minmax(0,1.9fr)]">
        <div class="rounded-[24px] border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-5 flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Filter dan Output</h2>
                    <p class="mt-1 text-sm text-slate-500">Atur periode rekap, lalu keluarkan laporan ke format yang dibutuhkan.</p>
                </div>
                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">{{ $periodMeta['label'] }}</span>
            </div>

            <form method="GET" action="{{ url('/absensi/rekap') }}" class="space-y-4">
                <div class="grid gap-3 md:grid-cols-2">
                    <label class="space-y-2">
                        <span class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Periode</span>
                        <select name="period" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200">
                            <option value="daily" @selected($periodMeta['period'] === 'daily')>Harian</option>
                            <option value="weekly" @selected($periodMeta['period'] === 'weekly')>Mingguan</option>
                            <option value="monthly" @selected($periodMeta['period'] === 'monthly')>Bulanan</option>
                            <option value="custom" @selected($periodMeta['period'] === 'custom')>Custom</option>
                        </select>
                    </label>
                    <label class="space-y-2">
                        <span class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Tanggal Acuan</span>
                        <input type="date" name="tanggal" value="{{ $periodMeta['anchor_date'] }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200">
                    </label>
                </div>

                <div class="grid gap-3 md:grid-cols-[minmax(0,1fr)_minmax(0,1fr)_minmax(0,1fr)]">
                    <label class="space-y-2">
                        <span class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Mulai</span>
                        <input type="date" name="tanggal_mulai" value="{{ $periodMeta['start_input'] }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200">
                    </label>
                    <label class="space-y-2">
                        <span class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Selesai</span>
                        <input type="date" name="tanggal_selesai" value="{{ $periodMeta['end_input'] }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200">
                    </label>
                    <label class="space-y-2">
                        <span class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Kelas</span>
                        <select name="kelas_id" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200">
                            <option value="">Semua Kelas</option>
                            @foreach($kelasList as $kelas)
                                <option value="{{ $kelas->id }}" @selected((string) $kelasId === (string) $kelas->id)>{{ $kelas->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>

                <div class="flex flex-wrap gap-2">
                    <a href="{{ url('/absensi/rekap') . '?' . http_build_query(array_merge($filterParams, ['period' => 'weekly', 'preset' => 'this_week'])) }}" class="rounded-full border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 transition hover:border-slate-300 hover:bg-slate-50">Minggu Ini</a>
                    <a href="{{ url('/absensi/rekap') . '?' . http_build_query(array_merge($filterParams, ['period' => 'monthly', 'preset' => 'this_month'])) }}" class="rounded-full border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 transition hover:border-slate-300 hover:bg-slate-50">Bulan Ini</a>
                    <a href="{{ url('/absensi/rekap') . '?' . http_build_query(array_merge($filterParams, ['period' => 'custom', 'preset' => 'last_7_days'])) }}" class="rounded-full border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 transition hover:border-slate-300 hover:bg-slate-50">7 Hari Terakhir</a>
                    <a href="{{ url('/absensi/rekap') . '?' . http_build_query(array_merge($filterParams, ['period' => 'custom', 'preset' => 'last_30_days'])) }}" class="rounded-full border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 transition hover:border-slate-300 hover:bg-slate-50">30 Hari Terakhir</a>
                    <button type="submit" class="rounded-full bg-slate-900 px-4 py-2 text-xs font-semibold text-white transition hover:bg-slate-800">Terapkan Filter</button>
                </div>
            </form>

            <div class="mt-5 grid gap-3 sm:grid-cols-3">
                <a href="{{ route('absensi.rekap.export', array_merge($filterParams, ['format' => 'csv'])) }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">Export CSV</a>
                <a href="{{ route('absensi.rekap.export', array_merge($filterParams, ['format' => 'pdf'])) }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">Export PDF</a>
                <a href="{{ route('absensi.rekap.class-report', $filterParams) }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 px-4 py-3 text-center text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">Template Laporan Kelas</a>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-[24px] border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-4 flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">KPI Rekap</h2>
                        <p class="mt-1 text-sm text-slate-500">Angka utama yang paling relevan untuk operasional guru.</p>
                    </div>
                </div>
                <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                    @foreach($kpiItems as $item)
                        <div class="rounded-2xl ring-1 {{ $toneClasses[$item['tone']] }} px-4 py-4">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.2em]">{{ $item['label'] }}</p>
                            <div class="mt-3 flex items-end gap-2">
                                <span class="text-3xl font-semibold tracking-tight">{{ $item['value'] }}</span>
                                <span class="pb-1 text-xs font-medium opacity-80">{{ $isSingleDay ? 'siswa' : 'catatan' }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-[24px] border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-5 flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Tren Kehadiran</h2>
                        <p class="mt-1 text-sm text-slate-500">Ringkasan cepat status hadir, izin, dan alpha pada rentang {{ strtolower($periodMeta['label']) }}.</p>
                    </div>
                    <div class="flex flex-wrap gap-2 text-xs text-slate-500">
                        <span class="rounded-full bg-slate-100 px-3 py-1.5">Puncak: <strong class="text-slate-700">{{ $trend['peak'] }}</strong></span>
                        <span class="rounded-full bg-slate-100 px-3 py-1.5">Rata-rata: <strong class="text-slate-700">{{ $trend['average'] }}</strong></span>
                    </div>
                </div>

                <div class="mb-4 flex flex-wrap gap-2 text-xs">
                    <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1.5 font-semibold text-emerald-700">
                        <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span> Hadir
                    </span>
                    <span class="inline-flex items-center gap-2 rounded-full bg-amber-50 px-3 py-1.5 font-semibold text-amber-700">
                        <span class="h-2.5 w-2.5 rounded-full bg-amber-400"></span> Izin
                    </span>
                    <span class="inline-flex items-center gap-2 rounded-full bg-rose-50 px-3 py-1.5 font-semibold text-rose-700">
                        <span class="h-2.5 w-2.5 rounded-full bg-rose-500"></span> Alpha
                    </span>
                </div>

                <div class="grid gap-6 lg:grid-cols-[minmax(0,1.8fr)_minmax(280px,0.9fr)]">
                    <div class="grid gap-2 items-end min-h-[220px]" style="grid-template-columns: repeat({{ $trendPointCount }}, minmax(0, 1fr));">
                    @forelse($trend['points'] as $point)
                        @php
                            $hadirHeight = $point['hadir'] > 0 ? max(8, (int) round(($point['hadir'] / max($trend['max'], 1)) * 160)) : 0;
                            $izinHeight = $point['izin'] > 0 ? max(8, (int) round(($point['izin'] / max($trend['max'], 1)) * 160)) : 0;
                            $alphaHeight = $point['alpha'] > 0 ? max(8, (int) round(($point['alpha'] / max($trend['max'], 1)) * 160)) : 0;
                        @endphp
                        <div class="flex flex-col items-center gap-2">
                            <span class="text-[11px] font-semibold text-slate-700">{{ $point['total'] }}</span>
                            <div class="flex w-full max-w-[38px] items-end justify-center gap-1">
                                <div class="w-2 rounded-t-full bg-emerald-500" style="height: {{ $hadirHeight }}px" title="Hadir {{ $point['hadir'] }}"></div>
                                <div class="w-2 rounded-t-full bg-amber-400" style="height: {{ $izinHeight }}px" title="Izin {{ $point['izin'] }}"></div>
                                <div class="w-2 rounded-t-full bg-rose-500" style="height: {{ $alphaHeight }}px" title="Alpha {{ $point['alpha'] }}"></div>
                            </div>
                            <span class="text-[11px] text-slate-500">{{ $point['label'] }}</span>
                        </div>
                    @empty
                        <div class="col-span-full rounded-2xl border border-dashed border-slate-200 bg-slate-50 px-4 py-8 text-center text-sm text-slate-500">
                            Belum ada data tren pada rentang ini.
                        </div>
                    @endforelse
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50/80 p-4">
                        <div class="mb-3">
                            <h3 class="text-sm font-semibold text-slate-900">Breakdown Per Kelas</h3>
                            <p class="mt-1 text-xs text-slate-500">Akumulasi status pada rentang yang sedang dibuka.</p>
                        </div>
                        <div class="space-y-3">
                            @forelse($trend['class_breakdown'] as $classPoint)
                                <div class="rounded-xl bg-white px-3 py-3 ring-1 ring-slate-200">
                                    <div class="flex items-center justify-between gap-3">
                                        <p class="text-sm font-semibold text-slate-900">{{ $classPoint['name'] }}</p>
                                        <span class="text-xs font-medium text-slate-500">{{ $classPoint['total'] }} catatan</span>
                                    </div>
                                    <div class="mt-3 grid grid-cols-3 gap-2 text-xs">
                                        <div class="rounded-lg bg-emerald-50 px-2 py-2 text-center font-semibold text-emerald-700">Hadir {{ $classPoint['hadir'] }}</div>
                                        <div class="rounded-lg bg-amber-50 px-2 py-2 text-center font-semibold text-amber-700">Izin {{ $classPoint['izin'] }}</div>
                                        <div class="rounded-lg bg-rose-50 px-2 py-2 text-center font-semibold text-rose-700">Alpha {{ $classPoint['alpha'] }}</div>
                                    </div>
                                </div>
                            @empty
                                <div class="rounded-xl border border-dashed border-slate-200 bg-white px-3 py-6 text-center text-sm text-slate-500">
                                    Belum ada breakdown kelas pada rentang ini.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="rounded-[24px] border border-slate-200 bg-white p-6 shadow-sm">
        <div class="mb-4 flex items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">Ringkasan Per Kelas</h2>
                <p class="mt-1 text-sm text-slate-500">Perbandingan cepat antar kelas untuk periode aktif.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead>
                    <tr class="text-left text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                        <th class="px-4 py-3">Kelas</th>
                        <th class="px-4 py-3">Total</th>
                        <th class="px-4 py-3">Hadir</th>
                        <th class="px-4 py-3">Izin</th>
                        <th class="px-4 py-3">Sakit</th>
                        <th class="px-4 py-3">Alpha</th>
                        <th class="px-4 py-3">{{ $isSingleDay ? 'Belum Masuk' : 'Tanpa Catatan' }}</th>
                        <th class="px-4 py-3">Terlambat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($classReports as $report)
                        <tr class="text-slate-700">
                            <td class="px-4 py-3 font-semibold text-slate-900">{{ $report['kelas']->nama_kelas }}</td>
                            <td class="px-4 py-3">{{ $report['summary']['total_siswa'] }}</td>
                            <td class="px-4 py-3">{{ $report['summary']['hadir'] }}</td>
                            <td class="px-4 py-3">{{ $report['summary']['izin'] }}</td>
                            <td class="px-4 py-3">{{ $report['summary']['sakit'] }}</td>
                            <td class="px-4 py-3">{{ $report['summary']['alpha'] }}</td>
                            <td class="px-4 py-3">{{ $report['summary']['belum_masuk'] }}</td>
                            <td class="px-4 py-3">{{ $report['summary']['terlambat'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-sm text-slate-500">Belum ada data siswa untuk ditampilkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    @foreach($classReports as $report)
        <section class="rounded-[24px] border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-4 flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">{{ $report['kelas']->nama_kelas }}</h2>
                    <p class="mt-1 text-sm text-slate-500">{{ $report['summary']['total_siswa'] }} siswa · Hadir {{ $report['summary']['hadir'] }} · {{ $isSingleDay ? 'Belum Masuk' : 'Tanpa Catatan' }} {{ $report['summary']['belum_masuk'] }}</p>
                </div>
                <div class="flex flex-wrap gap-2 text-xs text-slate-500">
                    <span class="rounded-full bg-slate-100 px-3 py-1.5">Izin {{ $report['summary']['izin'] }}</span>
                    <span class="rounded-full bg-slate-100 px-3 py-1.5">Sakit {{ $report['summary']['sakit'] }}</span>
                    <span class="rounded-full bg-slate-100 px-3 py-1.5">Alpha {{ $report['summary']['alpha'] }}</span>
                    <span class="rounded-full bg-slate-100 px-3 py-1.5">Foto {{ $report['summary']['foto_bukti'] }}</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead>
                        <tr class="text-left text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                            <th class="px-4 py-3">NIS</th>
                            <th class="px-4 py-3">Nama</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">{{ $isSingleDay ? 'Masuk' : 'Hadir' }}</th>
                            <th class="px-4 py-3">{{ $isSingleDay ? 'Pulang' : 'Izin' }}</th>
                            <th class="px-4 py-3">{{ $isSingleDay ? 'Terlambat' : 'Sakit' }}</th>
                            <th class="px-4 py-3">{{ $isSingleDay ? 'Keterangan' : 'Alpha' }}</th>
                            <th class="px-4 py-3">{{ $isSingleDay ? 'Foto' : 'Terakhir' }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($report['rows'] as $row)
                            @php
                                $badgeTone = match($row['status']) {
                                    'hadir' => 'bg-emerald-50 text-emerald-700',
                                    'izin' => 'bg-amber-50 text-amber-700',
                                    'sakit' => 'bg-sky-50 text-sky-700',
                                    'alpha' => 'bg-rose-50 text-rose-700',
                                    default => 'bg-slate-100 text-slate-700',
                                };
                            @endphp
                            <tr class="align-top text-slate-700">
                                <td class="px-4 py-3 font-medium">{{ $row['nis'] }}</td>
                                <td class="px-4 py-3 font-medium text-slate-900">{{ $row['nama'] }}</td>
                                <td class="px-4 py-3"><span class="rounded-full px-3 py-1 text-xs font-semibold {{ $badgeTone }}">{{ $row['status_label'] }}</span></td>
                                <td class="px-4 py-3">{{ $isSingleDay ? ($row['jam_masuk'] ?? '-') : $row['total_hadir'] }}</td>
                                <td class="px-4 py-3">{{ $isSingleDay ? ($row['jam_pulang'] ?? '-') : $row['total_izin'] }}</td>
                                <td class="px-4 py-3">{{ $isSingleDay ? ($row['terlambat'] ? 'Ya' : 'Tidak') : $row['total_sakit'] }}</td>
                                <td class="px-4 py-3">{{ $isSingleDay ? ($row['keterangan'] ?? '-') : $row['total_alpha'] }}</td>
                                <td class="px-4 py-3">{{ $isSingleDay ? ($row['foto_masuk'] ?? '-') : ($row['last_tanggal'] ?? '-') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    @endforeach
</div>
</x-app-layout>
