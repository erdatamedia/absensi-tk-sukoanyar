<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Data Kelas</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
                <h2 class="text-lg font-semibold text-gray-900">Data Kelas</h2>

                <div class="text-sm text-indigo-700 flex flex-wrap gap-3">
                    <a href="/siswa" class="hover:underline">Data Siswa</a>
                    <a href="/jadwal" class="hover:underline">Jadwal Pelajaran</a>
                    <a href="/absensi/riwayat" class="hover:underline">Riwayat Absensi</a>
                </div>

                @if(session('success'))
                    <p class="rounded-md border border-green-200 bg-green-50 px-3 py-2 text-sm text-green-700">{{ session('success') }}</p>
                @endif
                @if(session('error'))
                    <p class="rounded-md border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">{{ session('error') }}</p>
                @endif

                <div class="rounded-lg border border-gray-200 p-4">
                    <h3 class="mb-3 font-semibold text-gray-900">Tambah Kelas</h3>
                    <form method="POST" action="/kelas" class="grid gap-3 sm:grid-cols-3 sm:items-end">
                        @csrf
                        <div>
                            <label for="nama_kelas" class="mb-1 block text-sm font-medium text-gray-700">Nama Kelas</label>
                            <input id="nama_kelas" type="text" name="nama_kelas" placeholder="Contoh: TK A" required class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="tahun_ajaran" class="mb-1 block text-sm font-medium text-gray-700">Tahun Ajaran</label>
                            <input id="tahun_ajaran" type="text" name="tahun_ajaran" placeholder="2025/2026" required class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <button type="submit" class="inline-flex h-10 items-center justify-center rounded-md bg-indigo-600 px-4 text-sm font-medium text-white hover:bg-indigo-500">Tambah</button>
                    </form>
                </div>

                <div class="w-full max-w-full overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full min-w-[760px] divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold">No</th>
                                <th class="px-4 py-3 text-left font-semibold">Nama Kelas</th>
                                <th class="px-4 py-3 text-left font-semibold">Tahun Ajaran</th>
                                <th class="px-4 py-3 text-left font-semibold">Jumlah Siswa</th>
                                <th class="px-4 py-3 text-left font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($kelasList as $kelas)
                                <tr>
                                    <td class="px-4 py-3">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3">
                                        <input form="form-update-{{ $kelas->id }}" type="text" name="nama_kelas" value="{{ $kelas->nama_kelas }}" required class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </td>
                                    <td class="px-4 py-3">
                                        <input form="form-update-{{ $kelas->id }}" type="text" name="tahun_ajaran" value="{{ $kelas->tahun_ajaran }}" required class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </td>
                                    <td class="px-4 py-3">{{ $kelas->siswa_count }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex flex-wrap gap-2">
                                            <form id="form-update-{{ $kelas->id }}" method="POST" action="/kelas/{{ $kelas->id }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="rounded-md border border-blue-300 px-3 py-1.5 text-xs font-medium text-blue-700 hover:bg-blue-50">Update</button>
                                            </form>
                                            <form method="POST" action="/kelas/{{ $kelas->id }}" onsubmit="return confirm('Hapus kelas ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-md border border-red-300 px-3 py-1.5 text-xs font-medium text-red-700 hover:bg-red-50">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-gray-500">Belum ada data kelas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
