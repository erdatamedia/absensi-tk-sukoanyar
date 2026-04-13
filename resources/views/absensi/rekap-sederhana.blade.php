<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Rekap Absensi Sederhana</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
                <h2 class="text-lg font-semibold text-gray-900">Siswa Hadir Hari Ini</h2>
                <div class="text-sm text-indigo-700 flex flex-wrap gap-3">
                    <a href="/absensi" class="hover:underline">Scan Absensi</a>
                    <a href="/absensi/monitor" class="hover:underline">Monitor Harian</a>
                    <a href="/absensi/riwayat" class="hover:underline">Riwayat Lengkap</a>
                </div>

                <form method="GET" action="/absensi/rekap" class="grid gap-3 sm:grid-cols-3 sm:items-end">
                    <div>
                        <label for="tanggal" class="mb-1 block text-sm font-medium text-gray-700">Tanggal</label>
                        <input id="tanggal" type="date" name="tanggal" value="{{ $tanggal }}" class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="kelas_id" class="mb-1 block text-sm font-medium text-gray-700">Kelas</label>
                        <select id="kelas_id" name="kelas_id" class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua Kelas</option>
                            @foreach($kelasList as $kelas)
                                <option value="{{ $kelas->id }}" {{ (string) $kelasId === (string) $kelas->id ? 'selected' : '' }}>{{ $kelas->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="inline-flex h-10 items-center justify-center rounded-md bg-indigo-600 px-4 text-sm font-medium text-white hover:bg-indigo-500">Terapkan</button>
                </form>

                <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                    Total hadir: <strong>{{ $hadirList->count() }}</strong>
                </div>

                <div class="w-full max-w-full overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full min-w-[860px] divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold">No</th>
                                <th class="px-4 py-3 text-left font-semibold">NIS</th>
                                <th class="px-4 py-3 text-left font-semibold">Nama</th>
                                <th class="px-4 py-3 text-left font-semibold">Kelas</th>
                                <th class="px-4 py-3 text-left font-semibold">Jam Masuk</th>
                                <th class="px-4 py-3 text-left font-semibold">Foto</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($hadirList as $item)
                                <tr>
                                    <td class="px-4 py-3">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3">{{ $item->siswa->nis ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $item->siswa->nama ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $item->siswa->kelas->nama_kelas ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $item->jam_masuk ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        @if($item->foto_masuk)
                                            <a href="{{ asset('storage/dataset/'.$item->foto_masuk) }}" target="_blank" class="inline-flex items-center rounded-md border border-gray-300 px-2 py-1 text-xs font-medium text-gray-700 hover:bg-gray-50">Lihat Foto</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">Belum ada siswa hadir pada tanggal ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
