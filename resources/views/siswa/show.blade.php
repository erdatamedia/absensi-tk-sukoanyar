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
                <h2 class="mt-1 text-2xl font-semibold leading-tight text-slate-900">Detail Siswa</h2>
                <p class="mt-1 text-sm text-slate-500">Lihat identitas siswa dan cetak kartu QR untuk keperluan absensi.</p>
            </div>
            <div class="inline-flex items-center rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
                Operasional {{ $operasionalMulai }} - {{ $operasionalSelesai }}:
                <span class="ml-2 font-semibold text-slate-900">{{ $jamSekarang }} WIB</span>
            </div>
        </div>
    </x-slot>

    <div class="px-4 py-6 sm:px-6 lg:px-8">
        <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_320px]">
            <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h1 class="text-xl font-semibold text-slate-900">{{ $siswa->nama }}</h1>
                        <p class="mt-1 text-sm text-slate-500">NIS {{ $siswa->nis }} • {{ $siswa->kelas->nama_kelas ?? '-' }}</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('siswa.card-pdf', $siswa) }}" target="_blank" class="rounded-2xl bg-slate-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-slate-800">Cetak Kartu PDF</a>
                        <a href="{{ route('siswa.edit', $siswa) }}" class="rounded-2xl border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Edit</a>
                    </div>
                </div>

                <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200">
                    <dl class="divide-y divide-slate-200 text-sm">
                        <div class="grid gap-2 px-4 py-4 sm:grid-cols-[220px_minmax(0,1fr)]"><dt class="font-medium text-slate-500">NIS</dt><dd class="text-slate-900">{{ $siswa->nis }}</dd></div>
                        <div class="grid gap-2 px-4 py-4 sm:grid-cols-[220px_minmax(0,1fr)]"><dt class="font-medium text-slate-500">Nama Siswa</dt><dd class="text-slate-900">{{ $siswa->nama }}</dd></div>
                        <div class="grid gap-2 px-4 py-4 sm:grid-cols-[220px_minmax(0,1fr)]"><dt class="font-medium text-slate-500">Kelas</dt><dd class="text-slate-900">{{ $siswa->kelas->nama_kelas ?? '-' }}</dd></div>
                        <div class="grid gap-2 px-4 py-4 sm:grid-cols-[220px_minmax(0,1fr)]"><dt class="font-medium text-slate-500">Jenis Kelamin</dt><dd class="text-slate-900">{{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</dd></div>
                        <div class="grid gap-2 px-4 py-4 sm:grid-cols-[220px_minmax(0,1fr)]"><dt class="font-medium text-slate-500">Tanggal Lahir</dt><dd class="text-slate-900">{{ $siswa->tanggal_lahir ? date('d F Y', strtotime($siswa->tanggal_lahir)) : '-' }}</dd></div>
                        <div class="grid gap-2 px-4 py-4 sm:grid-cols-[220px_minmax(0,1fr)]"><dt class="font-medium text-slate-500">Keterangan</dt><dd class="text-slate-900">{{ $siswa->keterangan ?: '-' }}</dd></div>
                        <div class="grid gap-2 px-4 py-4 sm:grid-cols-[220px_minmax(0,1fr)]"><dt class="font-medium text-slate-500">Alamat</dt><dd class="text-slate-900">{{ $siswa->alamat ?: '-' }}</dd></div>
                        <div class="grid gap-2 px-4 py-4 sm:grid-cols-[220px_minmax(0,1fr)]"><dt class="font-medium text-slate-500">QR Token</dt><dd class="break-all text-slate-900">{{ $siswa->qr_token }}</dd></div>
                    </dl>
                </div>
            </section>

            <aside class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Kartu Siswa</p>
                <div class="mt-4 rounded-3xl border border-slate-200 bg-slate-50 p-5 text-center">
                    <div class="inline-flex items-center rounded-full bg-slate-900 px-3 py-1 text-xs font-semibold uppercase tracking-[0.24em] text-white">{{ $siswa->kelas->nama_kelas ?? 'Kelas' }}</div>
                    <p class="mt-4 text-lg font-semibold text-slate-900">{{ $siswa->nama }}</p>
                    <p class="mt-1 text-sm text-slate-500">NIS {{ $siswa->nis }}</p>
                    <div class="mt-5 flex justify-center rounded-3xl bg-white p-4 shadow-sm">{!! QrCode::size(180)->generate($siswa->qr_token) !!}</div>
                </div>
                <a href="{{ route('siswa.index') }}" class="mt-4 inline-flex items-center rounded-2xl border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Kembali ke Data Siswa</a>
            </aside>
        </div>
    </div>
</x-app-layout>
