<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Jadwal Pelajaran</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
                <h2 class="text-lg font-semibold text-gray-900">Jadwal Pelajaran</h2>

                <div class="text-sm text-indigo-700 flex flex-wrap gap-3">
                    <a href="/kelas" class="hover:underline">Data Kelas</a>
                    <a href="/siswa" class="hover:underline">Data Siswa</a>
                    <a href="/absensi" class="hover:underline">Scan Absensi</a>
                    <a href="/absensi/riwayat" class="hover:underline">Riwayat Absensi</a>
                </div>

                @if(session('success'))
                    <p class="rounded-md border border-green-200 bg-green-50 px-3 py-2 text-sm text-green-700">{{ session('success') }}</p>
                @endif
                @if(session('error'))
                    <p class="rounded-md border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">{{ session('error') }}</p>
                @endif

                <div class="grid gap-4 lg:grid-cols-2">
                    <div class="rounded-lg border border-gray-200 p-4 space-y-3">
                        <h3 class="font-semibold text-gray-900">Tambah Mata Pelajaran</h3>
                        <form method="POST" action="/jadwal/mapel" class="flex flex-wrap gap-2">
                            @csrf
                            <input type="text" name="nama" placeholder="Contoh: Matematika" required class="flex-1 min-w-[200px] rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-500">Tambah</button>
                        </form>

                        <h4 class="font-medium text-gray-800">Daftar Mata Pelajaran</h4>
                        <div class="w-full max-w-full overflow-x-auto rounded-md border border-gray-200">
                            <table class="w-full min-w-[760px] divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50 text-gray-700">
                                    <tr>
                                        <th class="px-3 py-2 text-left font-semibold">Nama</th>
                                        <th class="px-3 py-2 text-left font-semibold">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    @forelse($mataPelajaranList as $mapel)
                                        <tr>
                                            <td class="px-3 py-2">
                                                <input form="mapel-update-{{ $mapel->id }}" type="text" name="nama" value="{{ $mapel->nama }}" required class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            </td>
                                            <td class="px-3 py-2">
                                                <div class="flex flex-wrap gap-2">
                                                    <form id="mapel-update-{{ $mapel->id }}" method="POST" action="/jadwal/mapel/{{ $mapel->id }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="rounded-md border border-blue-300 px-2.5 py-1 text-xs font-medium text-blue-700 hover:bg-blue-50">Update</button>
                                                    </form>
                                                    <form method="POST" action="/jadwal/mapel/{{ $mapel->id }}" onsubmit="return confirm('Hapus mata pelajaran ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="rounded-md border border-red-300 px-2.5 py-1 text-xs font-medium text-red-700 hover:bg-red-50">Hapus</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="2" class="px-3 py-4 text-center text-gray-500">Belum ada mata pelajaran.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="rounded-lg border border-gray-200 p-4 space-y-3">
                        <h3 class="font-semibold text-gray-900">Tambah Jadwal</h3>
                        <form method="POST" action="/jadwal" class="grid gap-3">
                            @csrf
                            <select name="kelas_id" required class="rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Pilih Kelas</option>
                                @foreach($kelasList as $kelas)
                                    <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                                @endforeach
                            </select>

                            <select name="mata_pelajaran_id" required class="rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Pilih Mata Pelajaran</option>
                                @foreach($mataPelajaranList as $mapel)
                                    <option value="{{ $mapel->id }}">{{ $mapel->nama }}</option>
                                @endforeach
                            </select>

                            <select name="hari" required class="rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Pilih Hari</option>
                                <option value="Senin">Senin</option>
                                <option value="Selasa">Selasa</option>
                                <option value="Rabu">Rabu</option>
                                <option value="Kamis">Kamis</option>
                                <option value="Jumat">Jumat</option>
                                <option value="Sabtu">Sabtu</option>
                            </select>

                            <label class="text-sm text-gray-700">Jam Mulai
                                <input type="time" name="jam_mulai" required class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </label>
                            <label class="text-sm text-gray-700">Jam Selesai
                                <input type="time" name="jam_selesai" required class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </label>

                            <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500">Simpan Jadwal</button>
                        </form>
                    </div>
                </div>

                <div class="w-full max-w-full overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full min-w-[1280px] divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold">No</th>
                                <th class="px-4 py-3 text-left font-semibold">Kelas</th>
                                <th class="px-4 py-3 text-left font-semibold">Mata Pelajaran</th>
                                <th class="px-4 py-3 text-left font-semibold">Hari</th>
                                <th class="px-4 py-3 text-left font-semibold">Jam Mulai</th>
                                <th class="px-4 py-3 text-left font-semibold">Jam Selesai</th>
                                <th class="px-4 py-3 text-left font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($jadwalList as $jadwal)
                                <tr>
                                    <td class="px-4 py-3">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3">
                                        <select form="jadwal-update-{{ $jadwal->id }}" name="kelas_id" required class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            @foreach($kelasList as $kelas)
                                                <option value="{{ $kelas->id }}" {{ (string) $jadwal->kelas_id === (string) $kelas->id ? 'selected' : '' }}>{{ $kelas->nama_kelas }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-4 py-3">
                                        <select form="jadwal-update-{{ $jadwal->id }}" name="mata_pelajaran_id" required class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            @foreach($mataPelajaranList as $mapel)
                                                <option value="{{ $mapel->id }}" {{ (string) $jadwal->mata_pelajaran_id === (string) $mapel->id ? 'selected' : '' }}>{{ $mapel->nama }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-4 py-3">
                                        <select form="jadwal-update-{{ $jadwal->id }}" name="hari" required class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $hari)
                                                <option value="{{ $hari }}" {{ $jadwal->hari === $hari ? 'selected' : '' }}>{{ $hari }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-4 py-3">
                                        <input form="jadwal-update-{{ $jadwal->id }}" type="time" name="jam_mulai" value="{{ \Illuminate\Support\Str::of($jadwal->jam_mulai)->substr(0, 5) }}" required class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </td>
                                    <td class="px-4 py-3">
                                        <input form="jadwal-update-{{ $jadwal->id }}" type="time" name="jam_selesai" value="{{ \Illuminate\Support\Str::of($jadwal->jam_selesai)->substr(0, 5) }}" required class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex flex-wrap gap-2">
                                            <form id="jadwal-update-{{ $jadwal->id }}" method="POST" action="/jadwal/{{ $jadwal->id }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="rounded-md border border-blue-300 px-3 py-1.5 text-xs font-medium text-blue-700 hover:bg-blue-50">Update</button>
                                            </form>
                                            <form method="POST" action="/jadwal/{{ $jadwal->id }}" onsubmit="return confirm('Hapus jadwal ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-md border border-red-300 px-3 py-1.5 text-xs font-medium text-red-700 hover:bg-red-50">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="px-4 py-6 text-center text-gray-500">Belum ada jadwal pelajaran.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
