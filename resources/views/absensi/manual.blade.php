<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Absensi Manual</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
                <h2 class="text-lg font-semibold text-gray-900">Absensi Manual Darurat</h2>
                <div class="text-sm text-indigo-700 flex flex-wrap gap-3">
                    <a href="/absensi" class="hover:underline">Scan Absensi</a>
                    <a href="/absensi/riwayat" class="hover:underline">Riwayat Absensi</a>
                    <a href="/absensi/monitor" class="hover:underline">Monitor Harian</a>
                </div>
                <p class="text-xs text-gray-500">Batas jam masuk manual: <strong>{{ $cutoffMasuk }}</strong> (bisa override bila diperlukan).</p>

                @if(session('success'))
                    <p class="rounded-md border border-green-200 bg-green-50 px-3 py-2 text-sm text-green-700">{{ session('success') }}</p>
                @endif
                @if(session('error'))
                    <p class="rounded-md border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">{{ session('error') }}</p>
                @endif

                <form method="GET" action="/absensi/manual" class="grid gap-3 sm:grid-cols-3 sm:items-end">
                    <div>
                        <label for="tanggal_filter" class="mb-1 block text-sm font-medium text-gray-700">Tanggal</label>
                        <input id="tanggal_filter" type="date" name="tanggal" value="{{ $tanggal }}" class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="kelas_filter" class="mb-1 block text-sm font-medium text-gray-700">Kelas</label>
                        <select id="kelas_filter" name="kelas_id" class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua Kelas</option>
                            @foreach($kelasList as $kelas)
                                <option value="{{ $kelas->id }}" {{ (string) $kelasId === (string) $kelas->id ? 'selected' : '' }}>{{ $kelas->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="inline-flex h-10 items-center justify-center rounded-md bg-indigo-600 px-4 text-sm font-medium text-white hover:bg-indigo-500">Terapkan Filter</button>
                </form>

                <form method="POST" action="/absensi/manual" class="grid gap-3 md:grid-cols-3">
                    @csrf
                    <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                    <input type="hidden" name="kelas_id" value="{{ $kelasId }}">

                    <div class="md:col-span-2">
                        <label for="siswa_id" class="mb-1 block text-sm font-medium text-gray-700">Siswa</label>
                        <select id="siswa_id" name="siswa_id" required class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Pilih Siswa</option>
                            @foreach($siswaList as $siswa)
                                <option value="{{ $siswa->id }}">{{ $siswa->nis }} - {{ $siswa->nama }} ({{ $siswa->kelas->nama_kelas ?? '-' }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="jenis" class="mb-1 block text-sm font-medium text-gray-700">Jenis</label>
                        <select id="jenis" name="jenis" required class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="masuk">Masuk</option>
                            <option value="pulang">Pulang</option>
                        </select>
                    </div>

                    <div>
                        <label for="jam" class="mb-1 block text-sm font-medium text-gray-700">Jam</label>
                        <input id="jam" type="time" name="jam" value="{{ now()->format('H:i') }}" class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label for="status" class="mb-1 block text-sm font-medium text-gray-700">Status (untuk masuk)</label>
                        <select id="status" name="status" class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="hadir">hadir</option>
                            <option value="izin">izin</option>
                            <option value="sakit">sakit</option>
                            <option value="alpha">alpha</option>
                        </select>
                    </div>

                    <div>
                        <label for="keterangan" class="mb-1 block text-sm font-medium text-gray-700">Keterangan (opsional)</label>
                        <input id="keterangan" type="text" name="keterangan" maxlength="255" placeholder="Contoh: Kamera rusak / siswa izin orang tua" class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <label class="flex items-center gap-2 text-sm text-gray-700 md:col-span-2">
                        <input type="checkbox" name="override_cutoff" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                        Override batas jam masuk
                    </label>

                    <div class="md:col-span-3">
                        <button type="submit" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500">Simpan Absensi Manual</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
