<x-app-layout>
    @php
        $operasionalMulai = \App\Support\Branding::operationalStart();
        $operasionalSelesai = \App\Support\Branding::operationalEnd();
        $jamSekarang = now()->format('H:i');
    @endphp

    <x-slot name="header">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Master Data</p>
                <h2 class="mt-1 text-2xl font-semibold leading-tight text-slate-900">Data Kelas</h2>
                <p class="mt-1 text-sm text-slate-500">Kelola struktur kelas agar siswa dapat dikelompokkan dengan benar di rekap absensi.</p>
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
                <div class="grid gap-6 xl:grid-cols-[minmax(0,1.2fr)_minmax(300px,0.8fr)] xl:items-center">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-300">Master Kelas</p>
                        <h1 class="mt-3 text-3xl font-semibold leading-tight">Kelompokkan siswa per kelas untuk menjaga rekap harian tetap rapi dan akurat.</h1>
                        <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-300">
                            Data kelas berpengaruh langsung ke filter dashboard, monitor, rekap harian, dan riwayat absensi.
                            Karena itu struktur kelas harus sederhana dan konsisten.
                        </p>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-1">
                        <div class="rounded-3xl border border-white/10 bg-white/10 p-5 backdrop-blur">
                            <p class="text-sm text-slate-300">Total Kelas</p>
                            <p class="mt-3 text-3xl font-semibold text-white">{{ $kelasList->count() }}</p>
                            <p class="mt-2 text-sm text-slate-300">Kelas aktif yang dipakai sistem.</p>
                        </div>
                        <div class="rounded-3xl border border-emerald-400/20 bg-emerald-400/10 p-5">
                            <p class="text-sm text-emerald-100">Total Siswa</p>
                            <p class="mt-3 text-3xl font-semibold text-white">{{ $kelasList->sum('siswa_count') }}</p>
                            <p class="mt-2 text-sm text-emerald-100/90">Distribusi siswa ke seluruh kelas.</p>
                        </div>
                    </div>
                </div>
            </section>

            @if(session('success'))
                <section class="rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('success') }}
                </section>
            @endif

            @if(session('error'))
                <section class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    {{ session('error') }}
                </section>
            @endif

            <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-base font-semibold text-slate-900">Tambah Kelas</h3>
                <p class="mt-1 text-sm text-slate-500">Masukkan kelas baru yang akan dipakai untuk pengelompokan siswa dan laporan absensi.</p>

                <form method="POST" action="/kelas" class="mt-5 grid gap-4 md:grid-cols-3 md:items-end">
                    @csrf
                    <div>
                        <label for="nama_kelas" class="mb-1.5 block text-sm font-medium text-slate-700">Nama Kelas</label>
                        <input id="nama_kelas" type="text" name="nama_kelas" placeholder="Contoh: TK A" required class="w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-slate-900 focus:ring-slate-900">
                    </div>
                    <div>
                        <label for="tahun_ajaran" class="mb-1.5 block text-sm font-medium text-slate-700">Tahun Ajaran</label>
                        <input id="tahun_ajaran" type="text" name="tahun_ajaran" placeholder="2025/2026" required class="w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-slate-900 focus:ring-slate-900">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full rounded-2xl bg-slate-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-slate-800">Tambah Kelas</button>
                    </div>
                </form>
            </section>

            <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Daftar Kelas</h3>
                        <p class="mt-1 text-sm text-slate-500">Update kelas jika ada perubahan penamaan atau tahun ajaran.</p>
                    </div>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-slate-600">
                        {{ $kelasList->count() }} kelas
                    </span>
                </div>

                <div class="mt-5 w-full max-w-full overflow-x-auto rounded-2xl border border-slate-200">
                    <table class="w-full min-w-[820px] divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50 text-slate-700">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold">No</th>
                                <th class="px-4 py-3 text-left font-semibold">Nama Kelas</th>
                                <th class="px-4 py-3 text-left font-semibold">Tahun Ajaran</th>
                                <th class="px-4 py-3 text-left font-semibold">Jumlah Siswa</th>
                                <th class="px-4 py-3 text-left font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse($kelasList as $kelas)
                                <tr>
                                    <td class="px-4 py-3 text-slate-700">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3">
                                        <input form="form-update-{{ $kelas->id }}" type="text" name="nama_kelas" value="{{ $kelas->nama_kelas }}" required class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-slate-900 focus:ring-slate-900">
                                    </td>
                                    <td class="px-4 py-3">
                                        <input form="form-update-{{ $kelas->id }}" type="text" name="tahun_ajaran" value="{{ $kelas->tahun_ajaran }}" required class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-slate-900 focus:ring-slate-900">
                                    </td>
                                    <td class="px-4 py-3 text-slate-700">{{ $kelas->siswa_count }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex flex-wrap gap-2">
                                            <form id="form-update-{{ $kelas->id }}" method="POST" action="/kelas/{{ $kelas->id }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="rounded-xl border border-blue-300 px-3 py-1.5 text-xs font-medium text-blue-700 transition hover:bg-blue-50">Update</button>
                                            </form>
                                            <form method="POST" action="/kelas/{{ $kelas->id }}" onsubmit="return confirm('Hapus kelas ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-xl border border-red-300 px-3 py-1.5 text-xs font-medium text-red-700 transition hover:bg-red-50">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-slate-500">Belum ada data kelas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
