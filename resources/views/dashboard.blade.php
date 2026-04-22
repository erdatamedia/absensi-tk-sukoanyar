<x-app-layout>
    @php
        $operasionalMulai = \App\Support\Branding::operationalStart();
        $operasionalSelesai = \App\Support\Branding::operationalEnd();
        $jamSekarang = now()->format('H:i');
    @endphp

    <x-slot name="header">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Operasional</p>
                <h2 class="mt-1 text-2xl font-semibold leading-tight text-slate-900">Dashboard Absensi</h2>
                <p class="mt-1 text-sm text-slate-500">Tampilan utama untuk operator sekolah: scan, pantau, dan rekap absensi harian.</p>
            </div>
            <div class="inline-flex items-center rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
                Operasional {{ $operasionalMulai }} - {{ $operasionalSelesai }}:
                <span class="ml-2 font-semibold text-slate-900">{{ $jamSekarang }} WIB</span>
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
                <div class="grid gap-6 xl:grid-cols-[minmax(0,1.3fr)_minmax(320px,0.7fr)] xl:items-center">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-300">Mode Kiosk</p>
                        <h1 class="mt-3 text-3xl font-semibold leading-tight">Fokuskan laptop pada scan masuk, scan pulang, dan rekap otomatis.</h1>
                        <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-300">
                            Siswa dari kelas mana pun datang ke titik absensi, menunjukkan QR, lalu melihat ke kamera untuk selfie bukti hadir.
                            Sistem menyimpan absensi dan dataset wajah secara otomatis.
                        </p>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-1">
                        <div class="rounded-3xl border border-white/10 bg-white/10 p-5 backdrop-blur">
                            <p class="text-sm text-slate-300">Masuk Hari Ini</p>
                            <p class="mt-3 text-3xl font-semibold text-white">{{ $stats['masuk_hari_ini'] }}</p>
                            <p class="mt-2 text-sm text-slate-300">Belum pulang: {{ $stats['belum_pulang_hari_ini'] }} siswa</p>
                        </div>
                        <div class="rounded-3xl border border-emerald-400/20 bg-emerald-400/10 p-5">
                            <p class="text-sm text-emerald-100">Status Absensi</p>
                            <p class="mt-3 text-3xl font-semibold text-white">{{ $stats['status_hari_ini']['hadir'] }} hadir</p>
                            <p class="mt-2 text-sm text-emerald-100/90">{{ $stats['status_hari_ini']['alpha'] }} alpha</p>
                        </div>
                    </div>
                </div>
            </section>

            @if($isAdmin)
                <section class="grid gap-4 lg:grid-cols-[minmax(0,260px)_1fr]">
                    <div class="rounded-[24px] border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-sm font-semibold text-slate-900">Filter Dashboard</p>
                        <p class="mt-1 text-sm text-slate-500">Opsional untuk melihat aktivitas per kelas.</p>
                        <form method="GET" action="/dashboard" class="mt-4 space-y-3">
                            <div>
                                <label for="kelas_id" class="mb-1.5 block text-sm font-medium text-slate-700">Kelas</label>
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

                    <div class="rounded-[24px] border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-sm font-semibold text-slate-900">Arahan Operator</p>
                        <p class="mt-1 text-sm text-slate-500">Gunakan sidebar untuk berpindah halaman. Dashboard ini hanya untuk memantau ringkasan harian.</p>
                        <ol class="mt-4 space-y-2 text-sm text-slate-600">
                            <li>1. Buka `Scan Absensi` saat operasional dimulai.</li>
                            <li>2. Pantau lonjakan data di `Monitor Absensi`.</li>
                            <li>3. Gunakan `Laporan Absensi` untuk rekap dan laporan cepat.</li>
                            <li>4. Masuk ke `Riwayat Absensi` hanya jika perlu audit atau koreksi.</li>
                        </ol>
                    </div>
                </section>
            @endif

            <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-[24px] border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-slate-500">Total Siswa</p>
                    <p class="mt-4 text-3xl font-semibold text-slate-900">{{ $stats['total_siswa'] }}</p>
                    <p class="mt-2 text-sm text-slate-500">Siswa yang siap ikut absensi.</p>
                </div>
                <div class="rounded-[24px] border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-slate-500">Total Kelas</p>
                    <p class="mt-4 text-3xl font-semibold text-slate-900">{{ $stats['total_kelas'] }}</p>
                    <p class="mt-2 text-sm text-slate-500">Kelas aktif di sekolah.</p>
                </div>
                <div class="rounded-[24px] border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-slate-500">Pulang Hari Ini</p>
                    <p class="mt-4 text-3xl font-semibold text-slate-900">{{ $stats['pulang_hari_ini'] }}</p>
                    <p class="mt-2 text-sm text-slate-500">Siswa yang sudah menyelesaikan absensi pulang.</p>
                </div>
                <div class="rounded-[24px] border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-slate-500">Alpha Hari Ini</p>
                    <p class="mt-4 text-3xl font-semibold text-slate-900">{{ $stats['status_hari_ini']['alpha'] }}</p>
                    <p class="mt-2 text-sm text-slate-500">Status alpha yang tercatat hari ini.</p>
                </div>
            </section>

            <section class="grid gap-6 xl:grid-cols-[minmax(0,1.2fr)_minmax(340px,0.8fr)]">
                <div class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">Distribusi Status Hari Ini</h3>
                            <p class="mt-1 text-sm text-slate-500">Ringkasan cepat untuk melihat kondisi absensi harian.</p>
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
                    <p class="mt-1 text-sm text-slate-500">Perubahan absensi terakhir yang tercatat hari ini.</p>
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
        </div>
    </div>
</x-app-layout>
