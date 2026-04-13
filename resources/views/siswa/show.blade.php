<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Siswa</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <h1 class="text-lg font-semibold text-gray-900">Detail Siswa</h1>
                    <div class="flex gap-2 text-sm">
                        <a href="{{ route('siswa.index') }}" class="rounded-md border border-gray-300 px-3 py-1.5 text-gray-700 hover:bg-gray-50">Kembali</a>
                        <a href="{{ route('siswa.edit', $siswa->id) }}" class="rounded-md border border-blue-300 px-3 py-1.5 text-blue-700 hover:bg-blue-50">Edit</a>
                    </div>
                </div>

                <div class="w-full max-w-full overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full min-w-[520px] divide-y divide-gray-200 text-sm">
                        <tbody class="divide-y divide-gray-100">
                            <tr><th class="w-44 bg-gray-50 px-4 py-3 text-left font-semibold text-gray-700">NIS</th><td class="px-4 py-3">{{ $siswa->nis }}</td></tr>
                            <tr><th class="bg-gray-50 px-4 py-3 text-left font-semibold text-gray-700">Nama</th><td class="px-4 py-3">{{ $siswa->nama }}</td></tr>
                            <tr><th class="bg-gray-50 px-4 py-3 text-left font-semibold text-gray-700">Kelas</th><td class="px-4 py-3">{{ $siswa->kelas->nama_kelas ?? '-' }}</td></tr>
                            <tr><th class="bg-gray-50 px-4 py-3 text-left font-semibold text-gray-700">Jenis Kelamin</th><td class="px-4 py-3">{{ $siswa->jenis_kelamin }}</td></tr>
                            <tr><th class="bg-gray-50 px-4 py-3 text-left font-semibold text-gray-700">Tanggal Lahir</th><td class="px-4 py-3">{{ $siswa->tanggal_lahir ?? '-' }}</td></tr>
                            <tr><th class="bg-gray-50 px-4 py-3 text-left font-semibold text-gray-700">QR Token</th><td class="px-4 py-3 break-all">{{ $siswa->qr_token }}</td></tr>
                            <tr>
                                <th class="bg-gray-50 px-4 py-3 text-left font-semibold text-gray-700">QR Code</th>
                                <td class="px-4 py-3">{!! QrCode::size(160)->generate($siswa->qr_token) !!}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
