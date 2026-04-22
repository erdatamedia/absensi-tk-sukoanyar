@csrf
@if(isset($siswa))
    @method('PUT')
@endif

<div class="grid gap-5 md:grid-cols-2">
    <div>
        <label for="nis" class="text-sm font-medium text-slate-700">NIS</label>
        <input id="nis" type="text" name="nis" value="{{ old('nis', $siswa->nis ?? '') }}" required class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-slate-900 focus:outline-none focus:ring-0">
        @error('nis')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="nama" class="text-sm font-medium text-slate-700">Nama Siswa</label>
        <input id="nama" type="text" name="nama" value="{{ old('nama', $siswa->nama ?? '') }}" required class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-slate-900 focus:outline-none focus:ring-0">
        @error('nama')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="kelas_id" class="text-sm font-medium text-slate-700">Kelas</label>
        <select id="kelas_id" name="kelas_id" required class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-slate-900 focus:outline-none focus:ring-0">
            <option value="">Pilih kelas</option>
            @foreach($kelas as $k)
                <option value="{{ $k->id }}" @selected((string) old('kelas_id', $siswa->kelas_id ?? '') === (string) $k->id)>{{ $k->nama_kelas }}</option>
            @endforeach
        </select>
        @error('kelas_id')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="jenis_kelamin" class="text-sm font-medium text-slate-700">Jenis Kelamin</label>
        <select id="jenis_kelamin" name="jenis_kelamin" required class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-slate-900 focus:outline-none focus:ring-0">
            <option value="L" @selected(old('jenis_kelamin', $siswa->jenis_kelamin ?? '') === 'L')>Laki-laki</option>
            <option value="P" @selected(old('jenis_kelamin', $siswa->jenis_kelamin ?? '') === 'P')>Perempuan</option>
        </select>
        @error('jenis_kelamin')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="tanggal_lahir" class="text-sm font-medium text-slate-700">Tanggal Lahir</label>
        <input id="tanggal_lahir" type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', isset($siswa) && $siswa->tanggal_lahir ? date('Y-m-d', strtotime($siswa->tanggal_lahir)) : '') }}" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-slate-900 focus:outline-none focus:ring-0">
        @error('tanggal_lahir')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="keterangan" class="text-sm font-medium text-slate-700">Keterangan</label>
        <input id="keterangan" type="text" name="keterangan" value="{{ old('keterangan', $siswa->keterangan ?? '') }}" placeholder="Contoh: Siswa pindahan" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-slate-900 focus:outline-none focus:ring-0">
        @error('keterangan')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
</div>

<div class="mt-5">
    <label for="alamat" class="text-sm font-medium text-slate-700">Alamat</label>
    <textarea id="alamat" name="alamat" rows="3" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-slate-900 focus:outline-none focus:ring-0">{{ old('alamat', $siswa->alamat ?? '') }}</textarea>
    @error('alamat')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
</div>

<div class="mt-6 flex flex-wrap items-center gap-3">
    <button type="submit" class="inline-flex items-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-medium text-white transition hover:bg-slate-800">
        {{ isset($siswa) ? 'Simpan Perubahan' : 'Simpan Siswa' }}
    </button>
    <a href="{{ route('siswa.index') }}" class="inline-flex items-center rounded-2xl border border-slate-300 px-5 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Kembali</a>
</div>
