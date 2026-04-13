<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Relasi Orang Tua - Siswa</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
                <h2 class="text-lg font-semibold text-gray-900">Relasi Orang Tua - Siswa</h2>

                <div class="text-sm text-indigo-700 flex flex-wrap gap-3">
                    <a href="/siswa" class="hover:underline">Data Siswa</a>
                    <a href="/absensi/riwayat" class="hover:underline">Riwayat Absensi</a>
                    <a href="/orang-tua/absensi-anak" class="hover:underline">Portal Orang Tua</a>
                    <a href="/orang-tua/masuk" target="_blank" class="hover:underline">Halaman Login Kode</a>
                </div>

                @if(session('success'))
                    <p class="rounded-md border border-green-200 bg-green-50 px-3 py-2 text-sm text-green-700">{{ session('success') }}</p>
                @endif
                @if(session('error'))
                    <p class="rounded-md border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">{{ session('error') }}</p>
                @endif

                <div class="rounded-lg border border-gray-200 p-4">
                    <h3 class="mb-3 font-semibold text-gray-900">Tambah Relasi</h3>
                    <form method="POST" action="/orang-tua/relasi" class="grid gap-3 md:grid-cols-4 md:items-end">
                        @csrf
                        <div>
                            <label for="user_id" class="mb-1 block text-sm font-medium text-gray-700">User Orang Tua</label>
                            <select id="user_id" name="user_id" required class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Pilih User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="siswa_id" class="mb-1 block text-sm font-medium text-gray-700">Siswa</label>
                            <select id="siswa_id" name="siswa_id" required class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Pilih Siswa</option>
                                @foreach($siswa as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }} - {{ $item->kelas->nama_kelas ?? '-' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="hubungan" class="mb-1 block text-sm font-medium text-gray-700">Hubungan</label>
                            <input id="hubungan" type="text" name="hubungan" placeholder="Ayah / Ibu / Wali" class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <button type="submit" class="inline-flex h-10 items-center justify-center rounded-md bg-indigo-600 px-4 text-sm font-medium text-white hover:bg-indigo-500">Simpan Relasi</button>
                    </form>
                </div>

                <h3 class="font-semibold text-gray-900">Daftar Relasi</h3>
                <div class="w-full max-w-full overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full min-w-[1050px] divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold">No</th>
                                <th class="px-4 py-3 text-left font-semibold">User Orang Tua</th>
                                <th class="px-4 py-3 text-left font-semibold">Email</th>
                                <th class="px-4 py-3 text-left font-semibold">Siswa</th>
                                <th class="px-4 py-3 text-left font-semibold">Kelas</th>
                                <th class="px-4 py-3 text-left font-semibold">Hubungan</th>
                                <th class="px-4 py-3 text-left font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($relasi as $item)
                                <tr>
                                    <td class="px-4 py-3">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3">{{ $item->user->name ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $item->user->email ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $item->siswa->nama ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $item->siswa->kelas->nama_kelas ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $item->hubungan ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        <form method="POST" action="/orang-tua/relasi/{{ $item->id }}" onsubmit="return confirm('Hapus relasi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-md border border-red-300 px-3 py-1.5 text-xs font-medium text-red-700 hover:bg-red-50">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="px-4 py-6 text-center text-gray-500">Belum ada relasi orang tua-siswa.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <h3 class="font-semibold text-gray-900">Kode Akses Orang Tua</h3>
                <div class="w-full max-w-full overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full min-w-[1050px] divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold">No</th>
                                <th class="px-4 py-3 text-left font-semibold">Nama User</th>
                                <th class="px-4 py-3 text-left font-semibold">Email</th>
                                <th class="px-4 py-3 text-left font-semibold">Role</th>
                                <th class="px-4 py-3 text-left font-semibold">Kode Aktif</th>
                                <th class="px-4 py-3 text-left font-semibold">Kedaluwarsa</th>
                                <th class="px-4 py-3 text-left font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($users as $user)
                                <tr>
                                    <td class="px-4 py-3">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3">{{ $user->name }}</td>
                                    <td class="px-4 py-3">{{ $user->email }}</td>
                                    <td class="px-4 py-3">{{ $user->role }}</td>
                                    <td class="px-4 py-3 font-mono text-xs">{{ $user->parent_access_code ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $user->parent_access_code_expires_at ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        @if($user->role === 'orang_tua')
                                            <form method="POST" action="/orang-tua/kode/{{ $user->id }}">
                                                @csrf
                                                <button type="submit" class="rounded-md border border-blue-300 px-3 py-1.5 text-xs font-medium text-blue-700 hover:bg-blue-50">Buat/Ubah Kode</button>
                                            </form>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="px-4 py-6 text-center text-gray-500">Belum ada user.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
