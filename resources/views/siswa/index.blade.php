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
                <h2 class="mt-1 text-2xl font-semibold leading-tight text-slate-900">Data Siswa</h2>
                <p class="mt-1 text-sm text-slate-500">Kelola siswa yang akan mengikuti flow scan QR dan selfie di titik absensi.</p>
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
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-300">Master Siswa</p>
                        <h1 class="mt-3 text-3xl font-semibold leading-tight">Pastikan setiap siswa punya identitas QR yang siap dipakai untuk absensi.</h1>
                        <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-300">
                            Data siswa di halaman ini langsung dipakai pada flow scan QR, selfie bukti hadir, dan rekap otomatis.
                            Template impor yang didukung mengikuti kolom: NIS, Nama Siswa, Tanggal Lahir, Jenis Kelamin, Kelas, Keterangan, dan Alamat.
                        </p>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-1">
                        <div class="rounded-3xl border border-white/10 bg-white/10 p-5 backdrop-blur">
                            <p class="text-sm text-slate-300">Total Siswa</p>
                            <p class="mt-3 text-3xl font-semibold text-white">{{ $siswa->count() }}</p>
                            <p class="mt-2 text-sm text-slate-300">Jumlah siswa aktif di sistem.</p>
                        </div>
                        <div class="rounded-3xl border border-emerald-400/20 bg-emerald-400/10 p-5">
                            <p class="text-sm text-emerald-100">QR Siap Pakai</p>
                            <p class="mt-3 text-3xl font-semibold text-white">{{ $siswa->whereNotNull('qr_token')->count() }}</p>
                            <p class="mt-2 text-sm text-emerald-100/90">Token QR yang siap untuk operasional scan.</p>
                        </div>
                    </div>
                </div>
            </section>

            @if(session('success'))
                <section class="rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('success') }}
                </section>
            @endif

            @if($errors->any())
                <section class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <p class="font-medium">Ada data yang perlu diperiksa.</p>
                    <ul class="mt-2 list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </section>
            @endif

            <section class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_360px]">
                <div class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">Daftar Siswa</h3>
                            <p class="mt-1 text-sm text-slate-500">Setiap siswa bisa dibuka detailnya, dicetak per kartu, atau dicetak massal dalam satu PDF.</p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('siswa.create') }}" class="inline-flex items-center rounded-2xl bg-slate-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-slate-800">Tambah Siswa</a>
                        </div>
                    </div>

                    <form method="GET" action="{{ route('siswa.cards-pdf') }}" target="_blank" class="mt-5 grid gap-4 rounded-2xl border border-slate-200 bg-slate-50 p-4 md:grid-cols-[minmax(0,1fr)_180px_180px_auto] md:items-end">
                        <div>
                            <label for="kelas_id" class="text-sm font-medium text-slate-700">Filter Kelas</label>
                            <select id="kelas_id" name="kelas_id" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-slate-900 focus:outline-none focus:ring-0">
                                <option value="">Semua kelas</option>
                                @foreach($kelas as $itemKelas)
                                    <option value="{{ $itemKelas->id }}">{{ $itemKelas->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="per_page" class="text-sm font-medium text-slate-700">Ukuran Cetak</label>
                            <select id="per_page" name="per_page" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-slate-900 focus:outline-none focus:ring-0">
                                <option value="1">1 siswa / halaman</option>
                                <option value="8">8 siswa / halaman</option>
                            </select>
                        </div>
                        <div>
                            <label for="show_address" class="text-sm font-medium text-slate-700">Alamat pada Kartu</label>
                            <select id="show_address" name="show_address" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-slate-900 focus:outline-none focus:ring-0">
                                <option value="1">Dengan alamat</option>
                                <option value="0">Tanpa alamat</option>
                            </select>
                        </div>
                        <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-medium text-white transition hover:bg-slate-800">Cetak Kartu</button>
                    </form>

                    <div class="mt-5 w-full max-w-full overflow-x-auto rounded-2xl border border-slate-200">
                        <table class="w-full min-w-[1180px] divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-50 text-slate-700">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold">NIS</th>
                                    <th class="px-4 py-3 text-left font-semibold">Nama</th>
                                    <th class="px-4 py-3 text-left font-semibold">Kelas</th>
                                    <th class="px-4 py-3 text-left font-semibold">Tanggal Lahir</th>
                                    <th class="px-4 py-3 text-left font-semibold">Alamat</th>
                                    <th class="px-4 py-3 text-left font-semibold">QR Code</th>
                                    <th class="px-4 py-3 text-left font-semibold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @forelse($siswa as $s)
                                    <tr>
                                        <td class="px-4 py-3 text-slate-700">{{ $s->nis }}</td>
                                        <td class="px-4 py-3 font-medium text-slate-900">
                                            <p>{{ $s->nama }}</p>
                                            <p class="mt-1 text-xs text-slate-500">{{ $s->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                                        </td>
                                        <td class="px-4 py-3 text-slate-700">{{ $s->kelas->nama_kelas ?? '-' }}</td>
                                        <td class="px-4 py-3 text-slate-700">{{ $s->tanggal_lahir ? date('d/m/Y', strtotime($s->tanggal_lahir)) : '-' }}</td>
                                        <td class="px-4 py-3 text-slate-700">{{ $s->alamat ?: '-' }}</td>
                                        <td class="px-4 py-3">{!! QrCode::size(88)->generate($s->qr_token) !!}</td>
                                        <td class="px-4 py-3">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <a href="{{ route('siswa.show', $s) }}" class="rounded-xl border border-slate-300 px-3 py-1.5 text-xs font-medium text-slate-700 transition hover:bg-slate-50">Detail</a>
                                                <a href="{{ route('siswa.card-pdf', [$s, 'show_address' => 1]) }}" target="_blank" class="rounded-xl border border-emerald-300 px-3 py-1.5 text-xs font-medium text-emerald-700 transition hover:bg-emerald-50">Cetak Kartu</a>
                                                <a href="{{ route('siswa.edit', $s) }}" class="rounded-xl border border-blue-300 px-3 py-1.5 text-xs font-medium text-blue-700 transition hover:bg-blue-50">Edit</a>
                                                <form method="POST" action="{{ route('siswa.destroy', $s) }}" onsubmit="return confirm('Hapus siswa ini?')" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="rounded-xl border border-red-300 px-3 py-1.5 text-xs font-medium text-red-700 transition hover:bg-red-50">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-8 text-center text-slate-500">Belum ada data siswa.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <aside class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Impor Excel</p>
                        <h3 class="mt-2 text-lg font-semibold text-slate-900">Impor dari template sekolah</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-500">Sistem membaca kolom `NIS`, `NAMA SISWA`, `TANGGAL LAHIR`, `JENIS KELAMIN`, `KELAS`, `KETERANGAN`, dan `ALAMAT`. Kelas dengan nilai `A` atau `B` akan dicocokkan ke kelas yang ada atau dibuat otomatis sebagai `TK A` atau `TK B`.</p>
                    </div>

                    <form method="POST" action="{{ route('siswa.import') }}" enctype="multipart/form-data" class="mt-6 space-y-4">
                        @csrf
                        <div>
                            <label for="file" class="text-sm font-medium text-slate-700">File Excel</label>
                            <input id="file" type="file" name="file" accept=".xlsx,.xls,.csv" required class="mt-2 block w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-700 file:mr-4 file:rounded-xl file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-slate-800">
                        </div>
                        <button type="submit" class="inline-flex items-center rounded-2xl bg-slate-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-slate-800">Impor Data Siswa</button>
                    </form>

                    <div class="mt-6 rounded-3xl border border-slate-200 bg-slate-50 p-5">
                        <p class="text-sm font-semibold text-slate-900">Catatan format</p>
                        <ul class="mt-3 space-y-2 text-sm text-slate-600">
                            <li>Header boleh berada di baris ke-3 seperti template yang Anda kirim.</li>
                            <li>Jika NIS sudah ada, data siswa akan diperbarui, bukan diduplikasi.</li>
                            <li>QR token tetap dipertahankan untuk siswa yang sudah ada.</li>
                        </ul>
                    </div>
                </aside>
            </section>
        </div>
    </div>
</x-app-layout>
