<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Overview</p>
                <h2 class="mt-1 text-2xl font-semibold leading-tight text-slate-900">Dashboard</h2>
                <p class="mt-1 text-sm text-slate-500">Ringkasan operasional absensi, jadwal, dan aktivitas hari ini.</p>
            </div>
            <div class="inline-flex items-center rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
                Tanggal aktif: <span class="ml-2 font-semibold text-slate-900">{{ $today }}</span>
            </div>
        </div>
    </x-slot>

    @php
        $isAdmin = auth()->user()->role === 'admin';
        $activityItems = $isAdmin ? $aktivitasTerbaru : $anakHariIni;
    @endphp

    <div class="px-4 py-6 sm:px-6 lg:px-8">
        <div class="space-y-6">
            <section class="overflow-hidden rounded-[28px] bg-slate-900 px-6 py-6 text-white shadow-sm sm:px-8">
                <div class="grid gap-6 xl:grid-cols-[minmax(0,1.3fr)_minmax(320px,0.7fr)] xl:items-start">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-300">Ringkasan Operasional</p>
                        <h1 class="mt-3 text-3xl font-semibold leading-tight">Pantau absensi, jadwal, dan aktivitas harian dalam satu tampilan.</h1>
                        <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-300">
                            Dashboard ini menjadi titik pantau utama untuk admin sekolah. Fokus utamanya adalah siapa yang hadir hari ini,
                            aktivitas terbaru, dan jadwal pelajaran yang sedang berjalan.
                        </p>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-1">
                        <div class="rounded-3xl border border-white/10 bg-white/10 p-5 backdrop-blur">
                            <p class="text-sm text-slate-300">Status Hari Ini</p>
                            <p class="mt-3 text-2xl font-semibold">{{ $stats['masuk_hari_ini'] }} siswa masuk</p>
                            <p class="mt-2 text-sm text-slate-300">Belum pulang: {{ $stats['belum_pulang_hari_ini'] }} siswa</p>
                        </div>
                        <div class="rounded-3xl border border-emerald-400/20 bg-emerald-400/10 p-5">
                            <p class="text-sm text-emerald-100">Jadwal Hari Ini</p>
                            <p class="mt-3 text-2xl font-semibold text-white">{{ $jadwalHariIni->count() }} sesi</p>
                            <p class="mt-2 text-sm text-emerald-100/90">{{ $hariIni }}</p>
                        </div>
                    </div>
                </div>
            </section>

            @if($isAdmin)
                <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-900">Filter Dashboard</p>
                            <p class="mt-1 text-sm text-slate-500">Gunakan filter kelas untuk mempersempit ringkasan data.</p>
                        </div>
                        <form method="GET" action="/dashboard" class="grid gap-3 sm:grid-cols-[minmax(0,220px)_auto] sm:items-end">
                            <div>
                                <label for="kelas_id" class="mb-1.5 block text-sm font-medium text-slate-700">Filter Kelas</label>
                                <select id="kelas_id" name="kelas_id" class="w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-slate-900 focus:ring-slate-900">
                                <option value="">Semua Kelas</option>
                                @foreach($kelasList as $kelas)
                                    <option value="{{ $kelas->id }}" {{ (string) $kelasFilter === (string) $kelas->id ? 'selected' : '' }}>{{ $kelas->nama_kelas }}</option>
                                @endforeach
                                </select>
                            </div>
                            <div class="flex gap-2">
                                <button type="submit" class="rounded-2xl bg-slate-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-slate-800">Terapkan</button>
                                @if($kelasFilter)
                                    <a href="/dashboard" class="rounded-2xl border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Reset</a>
                                @endif
                            </div>
                        </form>
                    </div>
                </section>
            @endif

            <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-[24px] border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-slate-500">Total Siswa</p>
                    <p class="mt-4 text-3xl font-semibold text-slate-900">{{ $stats['total_siswa'] }}</p>
                    <p class="mt-2 text-sm text-slate-500">Jumlah siswa terdaftar di sistem.</p>
                </div>
                <div class="rounded-[24px] border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-slate-500">Masuk Hari Ini</p>
                    <p class="mt-4 text-3xl font-semibold text-slate-900">{{ $stats['masuk_hari_ini'] }}</p>
                    <p class="mt-2 text-sm text-slate-500">Absensi masuk yang berhasil dicatat hari ini.</p>
                </div>
                <div class="rounded-[24px] border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-slate-500">Pulang Hari Ini</p>
                    <p class="mt-4 text-3xl font-semibold text-slate-900">{{ $stats['pulang_hari_ini'] }}</p>
                    <p class="mt-2 text-sm text-slate-500">Absensi pulang yang tercatat pada hari yang sama.</p>
                </div>
                <div class="rounded-[24px] border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-slate-500">Belum Pulang</p>
                    <p class="mt-4 text-3xl font-semibold text-slate-900">{{ $stats['belum_pulang_hari_ini'] }}</p>
                    <p class="mt-2 text-sm text-slate-500">Siswa yang sudah masuk tetapi belum tercatat pulang.</p>
                </div>
            </section>

            <section class="grid gap-6 xl:grid-cols-[minmax(0,1.2fr)_minmax(340px,0.8fr)]">
                <div class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">Status Kehadiran Hari Ini</h3>
                            <p class="mt-1 text-sm text-slate-500">Distribusi status absensi berdasarkan pencatatan hari ini.</p>
                        </div>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-slate-600">Live</span>
                    </div>

                    <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                        <div class="rounded-2xl border border-green-200 bg-green-50 p-4">
                            <p class="text-sm text-green-700">Hadir</p>
                            <p class="mt-2 text-2xl font-semibold text-green-900">{{ $stats['status_hari_ini']['hadir'] }}</p>
                        </div>
                        <div class="rounded-2xl border border-blue-200 bg-blue-50 p-4">
                            <p class="text-sm text-blue-700">Izin</p>
                            <p class="mt-2 text-2xl font-semibold text-blue-900">{{ $stats['status_hari_ini']['izin'] }}</p>
                        </div>
                        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4">
                            <p class="text-sm text-amber-700">Sakit</p>
                            <p class="mt-2 text-2xl font-semibold text-amber-900">{{ $stats['status_hari_ini']['sakit'] }}</p>
                        </div>
                        <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4">
                            <p class="text-sm text-rose-700">Alpha</p>
                            <p class="mt-2 text-2xl font-semibold text-rose-900">{{ $stats['status_hari_ini']['alpha'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-base font-semibold text-slate-900">Aktivitas Terbaru</h3>
                    <p class="mt-1 text-sm text-slate-500">Perubahan absensi terakhir yang tercatat di sistem.</p>
                    <div class="mt-5 space-y-3">
                        @forelse($activityItems as $item)
                            <div class="rounded-2xl border border-slate-200 p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <p class="truncate font-medium text-slate-900">{{ $item->siswa->nama ?? '-' }}</p>
                                        <p class="mt-1 text-sm text-slate-500">{{ $item->siswa->kelas->nama_kelas ?? '-' }}</p>
                                    </div>
                                    <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600">{{ $item->status }}</span>
                                </div>
                                <p class="mt-3 text-sm text-slate-500">{{ $item->updated_at }}</p>
                            </div>
                        @empty
                            <div class="rounded-2xl border border-dashed border-slate-300 p-5 text-sm text-slate-500">
                                Belum ada aktivitas absensi hari ini.
                            </div>
                        @endforelse
                    </div>
                </div>
            </section>

            <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Jadwal Pelajaran Hari Ini</h3>
                        <p class="mt-1 text-sm text-slate-500">{{ $hariIni }}. Gunakan tabel ini untuk melihat sesi yang aktif, berikutnya, atau sudah selesai.</p>
                    </div>
                    <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-slate-600">{{ $jamSekarang }}</span>
                </div>

                <div class="mt-5 w-full max-w-full overflow-x-auto rounded-2xl border border-slate-200">
                    <table class="w-full min-w-[700px] divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50 text-slate-700">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold">Kelas</th>
                                <th class="px-4 py-3 text-left font-semibold">Mata Pelajaran</th>
                                <th class="px-4 py-3 text-left font-semibold">Jam</th>
                                <th class="px-4 py-3 text-left font-semibold">Kondisi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse($jadwalHariIni as $jadwal)
                                @php
                                    $isAktif = $jamSekarang >= $jadwal->jam_mulai && $jamSekarang < $jadwal->jam_selesai;
                                    $isDatang = $jamSekarang < $jadwal->jam_mulai;
                                    $statusText = $isAktif ? 'Sedang berlangsung' : ($isDatang ? 'Akan datang' : 'Selesai');
                                    $statusClass = $isAktif
                                        ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
                                        : ($isDatang ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-slate-100 text-slate-600 border-slate-200');
                                @endphp
                                <tr>
                                    <td class="px-4 py-3 text-slate-700">{{ $jadwal->kelas->nama_kelas ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-700">{{ $jadwal->mataPelajaran->nama ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-700">{{ \Illuminate\Support\Str::of($jadwal->jam_mulai)->substr(0, 5) }} - {{ \Illuminate\Support\Str::of($jadwal->jam_selesai)->substr(0, 5) }}</td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex rounded-full border px-2.5 py-1 text-xs font-medium {{ $statusClass }}">{{ $statusText }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-slate-500">Tidak ada jadwal hari ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
