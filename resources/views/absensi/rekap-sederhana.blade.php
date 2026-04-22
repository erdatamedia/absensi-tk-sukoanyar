<x-app-layout>
    @php
        $operasionalMulai = \App\Support\Branding::operationalStart();
        $operasionalSelesai = \App\Support\Branding::operationalEnd();
        $jamSekarang = now()->format('H:i');
    @endphp

    <x-slot name="header">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Absensi</p>
                <h2 class="mt-1 text-2xl font-semibold leading-tight text-slate-900">Rekap Harian</h2>
                <p class="mt-1 text-sm text-slate-500">Daftar otomatis siswa yang sudah hadir berdasarkan scan QR dan selfie.</p>
            </div>
            <div class="inline-flex items-center rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
                Operasional {{ $operasionalMulai }} - {{ $operasionalSelesai }}:
                <span class="ml-2 font-semibold text-slate-900">{{ $jamSekarang }} WIB</span>
            </div>
        </div>
    </x-slot>

    <div class="px-4 py-6 sm:px-6 lg:px-8">
        <div class="space-y-6">
            <section class="overflow-hidden rounded-[28px] bg-slate-900 px-6 py-6 text-white shadow-sm sm:px-8">
                <div class="grid gap-6 xl:grid-cols-[minmax(0,1.2fr)_minmax(320px,0.8fr)] xl:items-center">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-300">Rekap Otomatis</p>
                        <h1 class="mt-3 text-3xl font-semibold leading-tight">Pantau siapa saja yang sudah datang dan tercatat hadir pada hari ini.</h1>
                        <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-300">
                            Rekap ini menjadi keluaran utama dari flow scan QR dan selfie. Operator bisa langsung melihat siapa yang sudah hadir,
                            jam masuknya, dan foto bukti hadir yang tersimpan sebagai dataset.
                        </p>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-1">
                        <div class="rounded-3xl border border-white/10 bg-white/10 p-5 backdrop-blur">
                            <p class="text-sm text-slate-300">Total Hadir</p>
                            <p class="mt-3 text-3xl font-semibold text-white">{{ $hadirList->count() }}</p>
                            <p class="mt-2 text-sm text-slate-300">Siswa dengan absensi masuk yang berhasil.</p>
                        </div>
                        <div class="rounded-3xl border border-emerald-400/20 bg-emerald-400/10 p-5">
                            <p class="text-sm text-emerald-100">Filter Kelas</p>
                            <p class="mt-3 text-xl font-semibold text-white">{{ $kelasId ? ($kelasList->firstWhere('id', $kelasId)->nama_kelas ?? 'Dipilih') : 'Semua Kelas' }}</p>
                            <p class="mt-2 text-sm text-emerald-100/90">Gunakan filter untuk laporan yang lebih fokus.</p>
                        </div>
                    </div>
                </div>
            </section>
            <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Filter Rekap</h3>
                        <p class="mt-1 text-sm text-slate-500">Pilih tanggal dan kelas untuk melihat rekap harian yang lebih spesifik.</p>
                    </div>
                </div>

                <form method="GET" action="/absensi/rekap" class="mt-5 grid gap-4 md:grid-cols-3">
                    <div>
                        <label for="tanggal" class="mb-1.5 block text-sm font-medium text-slate-700">Tanggal</label>
                        <input id="tanggal" type="date" name="tanggal" value="{{ $tanggal }}" class="w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-slate-900 focus:ring-slate-900">
                    </div>
                    <div>
                        <label for="kelas_id" class="mb-1.5 block text-sm font-medium text-slate-700">Kelas</label>
                        <select id="kelas_id" name="kelas_id" class="w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-slate-900 focus:ring-slate-900">
                            <option value="">Semua Kelas</option>
                            @foreach($kelasList as $kelas)
                                <option value="{{ $kelas->id }}" {{ (string) $kelasId === (string) $kelas->id ? 'selected' : '' }}>{{ $kelas->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full rounded-2xl bg-slate-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-slate-800">Terapkan Filter</button>
                    </div>
                </form>
            </section>

            <section class="grid gap-4 sm:grid-cols-3">
                <div class="rounded-[24px] border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-slate-500">Siswa Hadir</p>
                    <p class="mt-4 text-3xl font-semibold text-slate-900">{{ $hadirList->count() }}</p>
                    <p class="mt-2 text-sm text-slate-500">Total siswa yang sudah masuk pada filter aktif.</p>
                </div>
                <div class="rounded-[24px] border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-slate-500">Dengan Foto Bukti</p>
                    <p class="mt-4 text-3xl font-semibold text-slate-900">{{ $hadirList->whereNotNull('foto_masuk')->count() }}</p>
                    <p class="mt-2 text-sm text-slate-500">Dataset selfie yang berhasil tersimpan.</p>
                </div>
                <div class="rounded-[24px] border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-slate-500">Sudah Pulang</p>
                    <p class="mt-4 text-3xl font-semibold text-slate-900">{{ $hadirList->whereNotNull('jam_pulang')->count() }}</p>
                    <p class="mt-2 text-sm text-slate-500">Siswa yang telah menyelesaikan absensi pulang.</p>
                </div>
            </section>

            <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Daftar Kehadiran</h3>
                        <p class="mt-1 text-sm text-slate-500">Urutan daftar hadir berdasarkan jam masuk yang tercatat di sistem.</p>
                    </div>
                    <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-slate-600">
                        {{ $hadirList->count() }} data
                    </span>
                </div>

                <div class="mt-5 w-full max-w-full overflow-x-auto rounded-2xl border border-slate-200">
                    <table class="w-full min-w-[1020px] divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50 text-slate-700">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold">No</th>
                                <th class="px-4 py-3 text-left font-semibold">NIS</th>
                                <th class="px-4 py-3 text-left font-semibold">Nama</th>
                                <th class="px-4 py-3 text-left font-semibold">Kelas</th>
                                <th class="px-4 py-3 text-left font-semibold">Jam Masuk</th>
                                <th class="px-4 py-3 text-left font-semibold">Jam Pulang</th>
                                <th class="px-4 py-3 text-left font-semibold">Status</th>
                                <th class="px-4 py-3 text-left font-semibold">Foto Bukti</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse($hadirList as $item)
                                <tr>
                                    <td class="px-4 py-3 text-slate-700">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3 text-slate-700">{{ $item->siswa->nis ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-700">{{ $item->siswa->nama ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-700">{{ $item->siswa->kelas->nama_kelas ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-700">{{ $item->jam_masuk ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-700">{{ $item->jam_pulang ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700">
                                            {{ $item->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($item->foto_masuk)
                                            <a href="{{ asset('storage/dataset/'.$item->foto_masuk) }}" target="_blank" class="inline-flex items-center rounded-xl border border-slate-300 px-3 py-1.5 text-xs font-medium text-slate-700 transition hover:bg-slate-50">Lihat Foto</a>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-8 text-center text-slate-500">Belum ada siswa hadir pada tanggal ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
