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
                <h2 class="mt-1 text-2xl font-semibold leading-tight text-slate-900">Edit Siswa</h2>
                <p class="mt-1 text-sm text-slate-500">Perbarui data siswa tanpa mengubah alur absensi utama.</p>
            </div>
            <div class="inline-flex items-center rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
                Operasional {{ $operasionalMulai }} - {{ $operasionalSelesai }}:
                <span class="ml-2 font-semibold text-slate-900">{{ $jamSekarang }} WIB</span>
            </div>
        </div>
    </x-slot>

    <div class="px-4 py-6 sm:px-6 lg:px-8">
        <section class="mx-auto max-w-4xl rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <div>
                <h1 class="text-xl font-semibold text-slate-900">{{ $siswa->nama }}</h1>
                <p class="mt-1 text-sm text-slate-500">NIS {{ $siswa->nis }} • {{ $siswa->kelas->nama_kelas ?? '-' }}</p>
            </div>

            <form method="POST" action="{{ route('siswa.update', $siswa->id) }}" class="mt-6">
                @include('siswa._form')
            </form>
        </section>
    </div>
</x-app-layout>
