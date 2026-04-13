<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Siswa</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
                <h1>Edit Siswa</h1>
                <p><a href="{{ route('siswa.index') }}">Kembali ke Data Siswa</a></p>
                
                <form method="POST" action="{{ route('siswa.update', $siswa->id) }}">
                    @csrf
                    @method('PUT')
                
                    <input type="text" name="nis" placeholder="NIS" value="{{ old('nis', $siswa->nis) }}"><br><br>
                
                    <input type="text" name="nama" placeholder="Nama" value="{{ old('nama', $siswa->nama) }}"><br><br>
                
                    <label>Kelas</label><br>
                    <select name="kelas_id">
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}" {{ (string) old('kelas_id', $siswa->kelas_id) === (string) $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select><br><br>
                
                    <label>Jenis Kelamin</label><br>
                    <select name="jenis_kelamin">
                        <option value="L" {{ old('jenis_kelamin', $siswa->jenis_kelamin) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin', $siswa->jenis_kelamin) === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select><br><br>
                
                    <label>Tanggal Lahir</label><br>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $siswa->tanggal_lahir) }}"><br><br>
                
                    <button type="submit">Update</button>
                </form>
                
            </div>
        </div>
    </div>
</x-app-layout>
