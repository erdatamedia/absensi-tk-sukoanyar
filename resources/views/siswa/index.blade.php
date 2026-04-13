<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Data Siswa</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-lg font-semibold text-gray-900">Data Siswa</h1>
                        <p class="text-sm text-gray-600">Kelola data siswa dan QR token absensi.</p>
                    </div>
                    <a href="{{ route('siswa.create') }}" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500">Tambah Siswa</a>
                </div>

                <div class="text-sm text-indigo-700 flex flex-wrap gap-3">
                    <a href="/kelas" class="hover:underline">Data Kelas</a>
                    <a href="/jadwal" class="hover:underline">Jadwal Pelajaran</a>
                    <a href="/absensi/riwayat" class="hover:underline">Riwayat Absensi</a>
                </div>

                @if(session('success'))
                    <p class="rounded-md border border-green-200 bg-green-50 px-3 py-2 text-sm text-green-700">{{ session('success') }}</p>
                @endif

                <div class="w-full max-w-full overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full min-w-[980px] divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold">NIS</th>
                                <th class="px-4 py-3 text-left font-semibold">Nama</th>
                                <th class="px-4 py-3 text-left font-semibold">Kelas</th>
                                <th class="px-4 py-3 text-left font-semibold">Jenis Kelamin</th>
                                <th class="px-4 py-3 text-left font-semibold">QR Code</th>
                                <th class="px-4 py-3 text-left font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($siswa as $s)
                                <tr>
                                    <td class="px-4 py-3">{{ $s->nis }}</td>
                                    <td class="px-4 py-3 font-medium text-gray-900">{{ $s->nama }}</td>
                                    <td class="px-4 py-3">{{ $s->kelas->nama_kelas ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $s->jenis_kelamin }}</td>
                                    <td class="px-4 py-3">{!! QrCode::size(120)->generate($s->qr_token) !!}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <a href="{{ route('siswa.show', $s->id) }}" class="rounded-md border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50">Detail</a>
                                            <a href="{{ route('siswa.edit', $s->id) }}" class="rounded-md border border-blue-300 px-3 py-1.5 text-xs font-medium text-blue-700 hover:bg-blue-50">Edit</a>
                                            <form method="POST" action="{{ route('siswa.destroy', $s->id) }}" onsubmit="return confirm('Hapus siswa ini?')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-md border border-red-300 px-3 py-1.5 text-xs font-medium text-red-700 hover:bg-red-50">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">Belum ada data siswa.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
