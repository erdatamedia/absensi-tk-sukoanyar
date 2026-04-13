<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Absensi Anak</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
                <h2 class="text-lg font-semibold text-gray-900">Absensi Anak Saya</h2>
                <div class="text-sm text-indigo-700 flex flex-wrap gap-3">
                    <a href="/orang-tua/relasi" class="hover:underline">Kelola Relasi Orang Tua</a>
                    <a href="/absensi/riwayat" class="hover:underline">Riwayat Sekolah</a>
                </div>

                <form method="GET" action="/orang-tua/absensi-anak" class="grid gap-3 sm:grid-cols-3 sm:items-end">
                    <div>
                        <label for="tanggal_dari" class="mb-1 block text-sm font-medium text-gray-700">Tanggal Dari</label>
                        <input id="tanggal_dari" type="date" name="tanggal_dari" value="{{ $tanggalDari }}" class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="tanggal_sampai" class="mb-1 block text-sm font-medium text-gray-700">Tanggal Sampai</label>
                        <input id="tanggal_sampai" type="date" name="tanggal_sampai" value="{{ $tanggalSampai }}" class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <button type="submit" class="inline-flex h-10 items-center justify-center rounded-md bg-indigo-600 px-4 text-sm font-medium text-white hover:bg-indigo-500">Filter</button>
                </form>

                <div class="w-full max-w-full overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full min-w-[760px] divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold">No</th>
                                <th class="px-4 py-3 text-left font-semibold">Tanggal</th>
                                <th class="px-4 py-3 text-left font-semibold">Nama Anak</th>
                                <th class="px-4 py-3 text-left font-semibold">Kelas</th>
                                <th class="px-4 py-3 text-left font-semibold">Jam Masuk</th>
                                <th class="px-4 py-3 text-left font-semibold">Jam Pulang</th>
                                <th class="px-4 py-3 text-left font-semibold">Status</th>
                                <th class="px-4 py-3 text-left font-semibold">Terlambat</th>
                                <th class="px-4 py-3 text-left font-semibold">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($absensiAnak as $item)
                                <tr>
                                    <td class="px-4 py-3">{{ $absensiAnak->firstItem() + $loop->index }}</td>
                                    <td class="px-4 py-3">{{ $item->tanggal }}</td>
                                    <td class="px-4 py-3">{{ $item->siswa->nama ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $item->siswa->kelas->nama_kelas ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $item->jam_masuk ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $item->jam_pulang ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $item->status }}</td>
                                    <td class="px-4 py-3">{{ $item->terlambat ? 'ya' : 'tidak' }}</td>
                                    <td class="px-4 py-3">{{ $item->keterangan ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="9" class="px-4 py-6 text-center text-gray-500">Belum ada data absensi untuk anak yang terhubung.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pt-2">{{ $absensiAnak->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
