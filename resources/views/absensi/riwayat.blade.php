<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Absensi</p>
                <h2 class="mt-1 text-2xl font-semibold leading-tight text-slate-900">Riwayat Absensi</h2>
                <p class="mt-1 text-sm text-slate-500">Kelola data absensi harian, lihat ringkasan kelas, dan lakukan koreksi data bila diperlukan.</p>
            </div>
            <div class="inline-flex items-center rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
                Tanggal aktif: <span class="ml-2 font-semibold text-slate-900">{{ $tanggal }}</span>
            </div>
        </div>
    </x-slot>

    <div class="px-4 py-6 sm:px-6 lg:px-8">
        <div class="space-y-6">
            <section class="overflow-hidden rounded-[28px] bg-slate-900 px-6 py-6 text-white shadow-sm sm:px-8">
                <div class="grid gap-6 xl:grid-cols-[minmax(0,1.2fr)_minmax(320px,0.8fr)] xl:items-center">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-300">Riwayat Harian</p>
                        <h1 class="mt-3 text-3xl font-semibold leading-tight">Saring, audit, dan perbaiki data absensi dari satu halaman kerja.</h1>
                        <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-300">
                            Halaman ini menggabungkan rekap kelas, daftar siswa yang belum masuk atau pulang, dan tabel riwayat detail.
                            Fokusnya adalah operasional admin, bukan hanya pelaporan.
                        </p>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-1">
                        <div class="rounded-3xl border border-white/10 bg-white/10 p-5 backdrop-blur">
                            <p class="text-sm text-slate-300">Total Data</p>
                            <p class="mt-3 text-3xl font-semibold text-white">{{ $summary['total'] }}</p>
                            <p class="mt-2 text-sm text-slate-300">Jumlah record absensi pada filter aktif.</p>
                        </div>
                        <div class="rounded-3xl border border-emerald-400/20 bg-emerald-400/10 p-5">
                            <p class="text-sm text-emerald-100">Sudah Pulang</p>
                            <p class="mt-3 text-3xl font-semibold text-white">{{ $summary['sudah_pulang'] }}</p>
                            <p class="mt-2 text-sm text-emerald-100/90">Belum pulang: {{ $summary['belum_pulang'] }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="flex flex-wrap gap-3 text-sm text-slate-600">
                <a href="/absensi" class="rounded-full border border-slate-200 bg-white px-4 py-2 transition hover:bg-slate-50">Buka Scan Absensi</a>
                <a href="/absensi/monitor" class="rounded-full border border-slate-200 bg-white px-4 py-2 transition hover:bg-slate-50">Monitor Harian</a>
                <a href="/absensi/manual" class="rounded-full border border-slate-200 bg-white px-4 py-2 transition hover:bg-slate-50">Input Manual</a>
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
                <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Filter Riwayat</h3>
                        <p class="mt-1 text-sm text-slate-500">Persempit data berdasarkan tanggal, kelas, siswa, status, sumber, dan indikator keterlambatan.</p>
                    </div>
                    <a href="/absensi/riwayat/export?tanggal={{ urlencode($tanggal) }}&kelas_id={{ urlencode((string) $kelasId) }}&q={{ urlencode((string) $q) }}&status_filter={{ urlencode((string) ($statusFilter ?? '')) }}&sumber_filter={{ urlencode((string) ($sumberFilter ?? '')) }}&terlambat_filter={{ urlencode((string) ($terlambatFilter ?? '')) }}" class="inline-flex items-center rounded-2xl border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                        Export CSV
                    </a>
                </div>

                <form method="GET" action="/absensi/riwayat" class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    <div>
                        <label for="tanggal" class="mb-1.5 block text-sm font-medium text-slate-700">Tanggal</label>
                        <input type="date" id="tanggal" name="tanggal" value="{{ $tanggal }}" class="w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-slate-900 focus:ring-slate-900">
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
                    <div>
                        <label for="q" class="mb-1.5 block text-sm font-medium text-slate-700">Cari Siswa</label>
                        <input type="text" id="q" name="q" placeholder="Nama atau NIS" value="{{ $q }}" class="w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-slate-900 focus:ring-slate-900">
                    </div>
                    <div>
                        <label for="status_filter" class="mb-1.5 block text-sm font-medium text-slate-700">Status</label>
                        <select id="status_filter" name="status_filter" class="w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-slate-900 focus:ring-slate-900">
                            <option value="">Semua Status</option>
                            <option value="hadir" {{ ($statusFilter ?? '') === 'hadir' ? 'selected' : '' }}>hadir</option>
                            <option value="izin" {{ ($statusFilter ?? '') === 'izin' ? 'selected' : '' }}>izin</option>
                            <option value="sakit" {{ ($statusFilter ?? '') === 'sakit' ? 'selected' : '' }}>sakit</option>
                            <option value="alpha" {{ ($statusFilter ?? '') === 'alpha' ? 'selected' : '' }}>alpha</option>
                        </select>
                    </div>
                    <div>
                        <label for="sumber_filter" class="mb-1.5 block text-sm font-medium text-slate-700">Sumber</label>
                        <select id="sumber_filter" name="sumber_filter" class="w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-slate-900 focus:ring-slate-900">
                            <option value="">Semua Sumber</option>
                            <option value="scan_qr" {{ ($sumberFilter ?? '') === 'scan_qr' ? 'selected' : '' }}>scan_qr</option>
                            <option value="manual" {{ ($sumberFilter ?? '') === 'manual' ? 'selected' : '' }}>manual</option>
                            <option value="auto_alpha" {{ ($sumberFilter ?? '') === 'auto_alpha' ? 'selected' : '' }}>auto_alpha</option>
                        </select>
                    </div>
                    <div>
                        <label for="terlambat_filter" class="mb-1.5 block text-sm font-medium text-slate-700">Terlambat</label>
                        <select id="terlambat_filter" name="terlambat_filter" class="w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-slate-900 focus:ring-slate-900">
                            <option value="">Semua</option>
                            <option value="ya" {{ ($terlambatFilter ?? '') === 'ya' ? 'selected' : '' }}>ya</option>
                            <option value="tidak" {{ ($terlambatFilter ?? '') === 'tidak' ? 'selected' : '' }}>tidak</option>
                        </select>
                    </div>
                    <div>
                        <label for="per_page" class="mb-1.5 block text-sm font-medium text-slate-700">Per Halaman</label>
                        <select id="per_page" name="per_page" class="w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-slate-900 focus:ring-slate-900">
                            <option value="10" {{ (string) $perPage === '10' ? 'selected' : '' }}>10</option>
                            <option value="20" {{ (string) $perPage === '20' ? 'selected' : '' }}>20</option>
                            <option value="50" {{ (string) $perPage === '50' ? 'selected' : '' }}>50</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full rounded-2xl bg-slate-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-slate-800">Terapkan Filter</button>
                    </div>
                </form>
            </section>

            <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Aksi Massal</h3>
                        <p class="mt-1 text-sm text-slate-500">Gunakan dengan hati-hati untuk menandai atau membatalkan alpha otomatis pada filter aktif.</p>
                    </div>
                </div>

                <div class="mt-5 flex flex-wrap gap-3">
                    <form method="POST" action="/absensi/mark-alpha">
                        @csrf
                        <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                        <input type="hidden" name="kelas_id" value="{{ $kelasId }}">
                        <input type="hidden" name="q" value="{{ $q }}">
                        <input type="hidden" name="status_filter" value="{{ $statusFilter }}">
                        <input type="hidden" name="sumber_filter" value="{{ $sumberFilter }}">
                        <input type="hidden" name="terlambat_filter" value="{{ $terlambatFilter }}">
                        <input type="hidden" name="per_page" value="{{ $perPage }}">
                        <button type="submit" onclick="return confirm('Tandai semua siswa yang belum absen pada filter ini sebagai alpha?')" class="rounded-2xl border border-red-300 bg-red-50 px-4 py-2.5 text-sm font-medium text-red-700 transition hover:bg-red-100">
                            Tandai Alpha Otomatis
                        </button>
                    </form>

                    <form method="POST" action="/absensi/unmark-alpha">
                        @csrf
                        <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                        <input type="hidden" name="kelas_id" value="{{ $kelasId }}">
                        <input type="hidden" name="q" value="{{ $q }}">
                        <input type="hidden" name="status_filter" value="{{ $statusFilter }}">
                        <input type="hidden" name="sumber_filter" value="{{ $sumberFilter }}">
                        <input type="hidden" name="terlambat_filter" value="{{ $terlambatFilter }}">
                        <input type="hidden" name="per_page" value="{{ $perPage }}">
                        <button type="submit" onclick="return confirm('Batalkan semua alpha otomatis pada filter ini?')" class="rounded-2xl border border-blue-300 bg-blue-50 px-4 py-2.5 text-sm font-medium text-blue-700 transition hover:bg-blue-100">
                            Batalkan Alpha Otomatis
                        </button>
                    </form>
                </div>
            </section>

            <section class="grid gap-4 sm:grid-cols-3">
                <div class="rounded-[24px] border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-slate-500">Total Data</p>
                    <p class="mt-4 text-3xl font-semibold text-slate-900">{{ $summary['total'] }}</p>
                    <p class="mt-2 text-sm text-slate-500">Jumlah seluruh data sesuai filter aktif.</p>
                </div>
                <div class="rounded-[24px] border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-slate-500">Sudah Pulang</p>
                    <p class="mt-4 text-3xl font-semibold text-slate-900">{{ $summary['sudah_pulang'] }}</p>
                    <p class="mt-2 text-sm text-slate-500">Data yang sudah memiliki jam pulang.</p>
                </div>
                <div class="rounded-[24px] border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-slate-500">Belum Pulang</p>
                    <p class="mt-4 text-3xl font-semibold text-slate-900">{{ $summary['belum_pulang'] }}</p>
                    <p class="mt-2 text-sm text-slate-500">Data yang masih menunggu absensi pulang.</p>
                </div>
            </section>

            <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-base font-semibold text-slate-900">Rekap Per Kelas</h3>
                <p class="mt-1 text-sm text-slate-500">Ringkasan cepat untuk melihat distribusi kehadiran per kelas pada tanggal yang dipilih.</p>

                <div class="mt-5 w-full max-w-full overflow-x-auto rounded-2xl border border-slate-200">
                    <table class="w-full min-w-[780px] divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50 text-slate-700">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold">Kelas</th>
                                <th class="px-4 py-3 text-left font-semibold">Total Siswa</th>
                                <th class="px-4 py-3 text-left font-semibold">Sudah Masuk</th>
                                <th class="px-4 py-3 text-left font-semibold">Sudah Pulang</th>
                                <th class="px-4 py-3 text-left font-semibold">Belum Masuk</th>
                                <th class="px-4 py-3 text-left font-semibold">Belum Pulang</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse($rekapKelas as $rekap)
                                <tr>
                                    <td class="px-4 py-3 text-slate-700">{{ $rekap->nama_kelas }}</td>
                                    <td class="px-4 py-3 text-slate-700">{{ $rekap->total_siswa }}</td>
                                    <td class="px-4 py-3 text-slate-700">{{ $rekap->total_masuk }}</td>
                                    <td class="px-4 py-3 text-slate-700">{{ $rekap->total_pulang }}</td>
                                    <td class="px-4 py-3 text-slate-700">{{ max($rekap->total_siswa - $rekap->total_masuk, 0) }}</td>
                                    <td class="px-4 py-3 text-slate-700">{{ max($rekap->total_masuk - $rekap->total_pulang, 0) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-slate-500">Data kelas belum tersedia.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="grid gap-6 xl:grid-cols-2">
                <div class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-base font-semibold text-slate-900">Siswa Belum Masuk</h3>
                    <p class="mt-1 text-sm text-slate-500">Daftar siswa yang belum memiliki absensi pada filter tanggal aktif.</p>

                    <div class="mt-5 w-full max-w-full overflow-x-auto rounded-2xl border border-slate-200">
                        <table class="w-full min-w-[620px] divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-50 text-slate-700">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold">No</th>
                                    <th class="px-4 py-3 text-left font-semibold">NIS</th>
                                    <th class="px-4 py-3 text-left font-semibold">Nama</th>
                                    <th class="px-4 py-3 text-left font-semibold">Kelas</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @forelse($siswaBelumMasuk as $siswa)
                                    <tr>
                                        <td class="px-4 py-3 text-slate-700">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-3 text-slate-700">{{ $siswa->nis }}</td>
                                        <td class="px-4 py-3 text-slate-700">{{ $siswa->nama }}</td>
                                        <td class="px-4 py-3 text-slate-700">{{ $siswa->kelas->nama_kelas ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-8 text-center text-slate-500">Semua siswa sudah masuk.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-base font-semibold text-slate-900">Siswa Belum Pulang</h3>
                    <p class="mt-1 text-sm text-slate-500">Daftar siswa yang sudah masuk tetapi belum memiliki catatan pulang.</p>

                    <div class="mt-5 w-full max-w-full overflow-x-auto rounded-2xl border border-slate-200">
                        <table class="w-full min-w-[620px] divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-50 text-slate-700">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold">No</th>
                                    <th class="px-4 py-3 text-left font-semibold">NIS</th>
                                    <th class="px-4 py-3 text-left font-semibold">Nama</th>
                                    <th class="px-4 py-3 text-left font-semibold">Kelas</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @forelse($siswaBelumPulang as $siswa)
                                    <tr>
                                        <td class="px-4 py-3 text-slate-700">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-3 text-slate-700">{{ $siswa->nis }}</td>
                                        <td class="px-4 py-3 text-slate-700">{{ $siswa->nama }}</td>
                                        <td class="px-4 py-3 text-slate-700">{{ $siswa->kelas->nama_kelas ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-8 text-center text-slate-500">Tidak ada siswa yang belum pulang.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Detail Riwayat</h3>
                        <p class="mt-1 text-sm text-slate-500">Area kerja utama untuk edit status, jam, melihat foto, dan menghapus data.</p>
                    </div>
                    <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-slate-600">
                        Halaman {{ $riwayat->currentPage() }} / {{ $riwayat->lastPage() }}
                    </span>
                </div>

                <div class="mt-5 w-full max-w-full overflow-x-auto rounded-2xl border border-slate-200">
                    <table class="w-full min-w-[1750px] divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50 text-slate-700">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold">No</th>
                                <th class="px-4 py-3 text-left font-semibold">NIS</th>
                                <th class="px-4 py-3 text-left font-semibold">Nama</th>
                                <th class="px-4 py-3 text-left font-semibold">Kelas</th>
                                <th class="px-4 py-3 text-left font-semibold">Tanggal</th>
                                <th class="px-4 py-3 text-left font-semibold">Jam Masuk</th>
                                <th class="px-4 py-3 text-left font-semibold">Jam Pulang</th>
                                <th class="px-4 py-3 text-left font-semibold">Status</th>
                                <th class="px-4 py-3 text-left font-semibold">Keterangan</th>
                                <th class="px-4 py-3 text-left font-semibold">Sumber</th>
                                <th class="px-4 py-3 text-left font-semibold">Terlambat</th>
                                <th class="px-4 py-3 text-left font-semibold">Foto Masuk</th>
                                <th class="px-4 py-3 text-left font-semibold">Foto Pulang</th>
                                <th class="px-4 py-3 text-left font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse($riwayat as $item)
                                <tr class="align-top">
                                    <td class="px-4 py-3 text-slate-700">{{ $riwayat->firstItem() + $loop->index }}</td>
                                    <td class="px-4 py-3 text-slate-700">{{ $item->siswa->nis ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-700">{{ $item->siswa->nama ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-700">{{ $item->siswa->kelas->nama_kelas ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-700">{{ $item->tanggal }}</td>
                                    <td class="px-4 py-3">
                                        <input form="form-waktu-{{ $item->id }}" type="time" name="jam_masuk" value="{{ $item->jam_masuk ? \Illuminate\Support\Str::of($item->jam_masuk)->substr(0, 5) : '' }}" class="w-24 rounded-xl border-slate-300 text-sm shadow-sm focus:border-slate-900 focus:ring-slate-900">
                                    </td>
                                    <td class="px-4 py-3">
                                        <input form="form-waktu-{{ $item->id }}" type="time" name="jam_pulang" value="{{ $item->jam_pulang ? \Illuminate\Support\Str::of($item->jam_pulang)->substr(0, 5) : '' }}" class="w-24 rounded-xl border-slate-300 text-sm shadow-sm focus:border-slate-900 focus:ring-slate-900">
                                    </td>
                                    <td class="px-4 py-3">
                                        <form method="POST" action="/absensi/{{ $item->id }}/status" class="space-y-2">
                                            @csrf
                                            <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                                            <input type="hidden" name="kelas_id" value="{{ $kelasId }}">
                                            <input type="hidden" name="q" value="{{ $q }}">
                                            <input type="hidden" name="status_filter" value="{{ $statusFilter }}">
                                            <input type="hidden" name="sumber_filter" value="{{ $sumberFilter }}">
                                            <input type="hidden" name="terlambat_filter" value="{{ $terlambatFilter }}">
                                            <input type="hidden" name="per_page" value="{{ $perPage }}">
                                            <input type="hidden" name="page" value="{{ $riwayat->currentPage() }}">
                                            <select name="status" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-slate-900 focus:ring-slate-900">
                                                <option value="hadir" {{ $item->status === 'hadir' ? 'selected' : '' }}>hadir</option>
                                                <option value="izin" {{ $item->status === 'izin' ? 'selected' : '' }}>izin</option>
                                                <option value="sakit" {{ $item->status === 'sakit' ? 'selected' : '' }}>sakit</option>
                                                <option value="alpha" {{ $item->status === 'alpha' ? 'selected' : '' }}>alpha</option>
                                            </select>
                                            <button type="submit" class="rounded-xl border border-blue-300 px-3 py-1.5 text-xs font-medium text-blue-700 transition hover:bg-blue-50">Simpan Status</button>
                                        </form>
                                    </td>
                                    <td class="px-4 py-3 text-slate-700">{{ $item->keterangan ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-700">
                                        <span class="inline-flex rounded-full border px-2.5 py-1 text-xs font-medium {{ $item->sumber === 'scan_qr' ? 'border-slate-200 bg-slate-100 text-slate-700' : ($item->sumber === 'manual' ? 'border-blue-200 bg-blue-50 text-blue-700' : 'border-amber-200 bg-amber-50 text-amber-700') }}">
                                            {{ $item->sumber ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-slate-700">
                                        <span class="inline-flex rounded-full border px-2.5 py-1 text-xs font-medium {{ $item->terlambat ? 'border-amber-200 bg-amber-50 text-amber-700' : 'border-slate-200 bg-slate-100 text-slate-600' }}">
                                            {{ $item->terlambat ? 'ya' : 'tidak' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($item->foto_masuk)
                                            <a href="{{ asset('storage/dataset/'.$item->foto_masuk) }}" target="_blank" class="inline-block">
                                                <img src="{{ asset('storage/dataset/'.$item->foto_masuk) }}" alt="Foto Masuk {{ $item->siswa->nama ?? '' }}" class="mb-2 h-16 w-16 rounded-xl border border-slate-200 object-cover">
                                                <span class="block max-w-24 truncate text-xs text-slate-500">{{ $item->foto_masuk }}</span>
                                            </a>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($item->foto_pulang)
                                            <a href="{{ asset('storage/dataset/'.$item->foto_pulang) }}" target="_blank" class="inline-block">
                                                <img src="{{ asset('storage/dataset/'.$item->foto_pulang) }}" alt="Foto Pulang {{ $item->siswa->nama ?? '' }}" class="mb-2 h-16 w-16 rounded-xl border border-slate-200 object-cover">
                                                <span class="block max-w-24 truncate text-xs text-slate-500">{{ $item->foto_pulang }}</span>
                                            </a>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="space-y-2">
                                            <form id="form-waktu-{{ $item->id }}" method="POST" action="/absensi/{{ $item->id }}/waktu">
                                                @csrf
                                                <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                                                <input type="hidden" name="kelas_id" value="{{ $kelasId }}">
                                                <input type="hidden" name="q" value="{{ $q }}">
                                                <input type="hidden" name="status_filter" value="{{ $statusFilter }}">
                                                <input type="hidden" name="sumber_filter" value="{{ $sumberFilter }}">
                                                <input type="hidden" name="terlambat_filter" value="{{ $terlambatFilter }}">
                                                <input type="hidden" name="per_page" value="{{ $perPage }}">
                                                <input type="hidden" name="page" value="{{ $riwayat->currentPage() }}">
                                                <button type="submit" class="w-full rounded-xl border border-teal-300 bg-teal-50 px-3 py-1.5 text-xs font-medium text-teal-700 transition hover:bg-teal-100">Simpan Jam</button>
                                            </form>

                                            <form method="POST" action="/absensi/{{ $item->id }}" onsubmit="return confirm('Hapus data absensi ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                                                <input type="hidden" name="kelas_id" value="{{ $kelasId }}">
                                                <input type="hidden" name="q" value="{{ $q }}">
                                                <input type="hidden" name="status_filter" value="{{ $statusFilter }}">
                                                <input type="hidden" name="sumber_filter" value="{{ $sumberFilter }}">
                                                <input type="hidden" name="terlambat_filter" value="{{ $terlambatFilter }}">
                                                <input type="hidden" name="per_page" value="{{ $perPage }}">
                                                <input type="hidden" name="page" value="{{ $riwayat->currentPage() }}">
                                                <button type="submit" class="w-full rounded-xl border border-red-300 bg-red-50 px-3 py-1.5 text-xs font-medium text-red-700 transition hover:bg-red-100">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="14" class="px-4 py-8 text-center text-slate-500">Belum ada data absensi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pt-4">{{ $riwayat->links() }}</div>
            </section>
        </div>
    </div>
</x-app-layout>
