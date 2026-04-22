<x-app-layout>
    @php
        $jamSekarang = now()->format('H:i');
        $operasionalMulai = $settings['operational_start'];
        $operasionalSelesai = $settings['operational_end'];
    @endphp

    <x-slot name="header">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Akun</p>
                <h2 class="mt-1 text-2xl font-semibold leading-tight text-slate-900">Pengaturan Sekolah</h2>
                <p class="mt-1 text-sm text-slate-500">Atur nama sekolah, tagline, dan logo yang dipakai pada layout aplikasi dan kartu siswa.</p>
            </div>
            <div class="inline-flex items-center rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
                Operasional {{ $operasionalMulai }} - {{ $operasionalSelesai }}:
                <span class="ml-2 font-semibold text-slate-900">{{ $jamSekarang }} WIB</span>
            </div>
        </div>
    </x-slot>

    <div class="px-4 py-6 sm:px-6 lg:px-8">
        <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_420px]">
            <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                @if(session('success'))
                    <div class="mb-5 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
                @endif

                @if($errors->any())
                    <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <ul class="list-disc pl-5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div>
                    <h1 class="text-xl font-semibold text-slate-900">Branding Sekolah</h1>
                    <p class="mt-1 text-sm text-slate-500">Perubahan di sini akan muncul di sidebar, halaman login, halaman home, dan kartu siswa PDF.</p>
                </div>

                <form method="POST" action="{{ route('settings.school.update') }}" enctype="multipart/form-data" class="mt-6 space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="school_name" class="text-sm font-medium text-slate-700">Nama Sekolah</label>
                        <input id="school_name" type="text" name="school_name" value="{{ old('school_name', $settings['school_name']) }}" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-slate-900 focus:outline-none focus:ring-0" required>
                    </div>

                    <div>
                        <label for="school_tagline" class="text-sm font-medium text-slate-700">Tagline</label>
                        <input id="school_tagline" type="text" name="school_tagline" value="{{ old('school_tagline', $settings['school_tagline']) }}" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-slate-900 focus:outline-none focus:ring-0" placeholder="Contoh: Sistem Absensi TK">
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label for="operational_start" class="text-sm font-medium text-slate-700">Jam Operasional Mulai</label>
                            <input id="operational_start" type="time" name="operational_start" value="{{ old('operational_start', $settings['operational_start']) }}" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-slate-900 focus:outline-none focus:ring-0" required>
                        </div>
                        <div>
                            <label for="operational_end" class="text-sm font-medium text-slate-700">Jam Operasional Selesai</label>
                            <input id="operational_end" type="time" name="operational_end" value="{{ old('operational_end', $settings['operational_end']) }}" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-slate-900 focus:outline-none focus:ring-0" required>
                        </div>
                    </div>

                    <div>
                        <label for="school_logo" class="text-sm font-medium text-slate-700">Logo Sekolah</label>
                        <input id="school_logo" type="file" name="school_logo" accept=".jpg,.jpeg,.png,.webp" class="mt-2 block w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-700 file:mr-4 file:rounded-xl file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-slate-800">
                        <p class="mt-2 text-xs text-slate-500">Disarankan PNG persegi dengan latar transparan. Maksimal 2 MB.</p>
                    </div>

                    @if($settings['school_logo_url'])
                        <label class="flex items-center gap-3 rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-600">
                            <input type="checkbox" name="remove_logo" value="1" class="rounded border-slate-300 text-slate-900 focus:ring-slate-900">
                            <span>Hapus logo saat ini</span>
                        </label>
                    @endif

                    <button type="submit" class="inline-flex items-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-medium text-white transition hover:bg-slate-800">Simpan Pengaturan</button>
                </form>
            </section>

            <aside class="space-y-6">
                <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Preview PDF</p>
                            <h3 class="mt-2 text-lg font-semibold text-slate-900">1 siswa per halaman</h3>
                            <p class="mt-1 text-xs text-slate-500">Preview default menampilkan alamat. PDF juga bisa dibuat tanpa alamat.</p>
                        </div>
                        <a href="{{ route('siswa.cards-pdf', ['per_page' => 1, 'show_address' => 1]) }}" target="_blank" class="rounded-2xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Cetak Kartu</a>
                    </div>

                    <div class="mt-5 rounded-[26px] bg-slate-100 p-4">
                        <div class="overflow-hidden rounded-[22px] border border-slate-200 bg-white shadow-sm">
                            <div class="h-3 bg-amber-500"></div>
                            <div class="border-b border-amber-100 bg-amber-50 px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-12 w-12 items-center justify-center overflow-hidden rounded-full border border-amber-200 bg-white text-sm font-semibold text-slate-900">
                                        @if($settings['school_logo_url'])
                                            <img src="{{ $settings['school_logo_url'] }}" alt="Logo sekolah" class="h-9 w-9 object-contain">
                                        @else
                                            {{ $schoolBranding['initials'] ?? 'AT' }}
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-[10px] uppercase tracking-[0.26em] text-amber-700">Kartu Absensi Siswa</p>
                                        <p class="text-xl font-semibold text-slate-950">{{ $settings['school_name'] }}</p>
                                        <p class="text-sm text-slate-500">{{ $settings['school_tagline'] ?: 'Sistem Absensi TK' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-[92px_1fr_120px] gap-3 px-5 py-4">
                                <div class="rounded-[16px] border border-dashed border-slate-300 bg-[linear-gradient(180deg,#f8fafc_0%,#eef2ff_100%)] px-3 py-4 text-center">
                                    <div class="mx-auto h-8 w-8 rounded-full border border-blue-200 bg-blue-100"></div>
                                    <div class="mx-auto mt-3 h-1.5 w-12 rounded-full bg-slate-300"></div>
                                    <p class="mt-4 text-[10px] font-semibold uppercase tracking-[0.16em] text-slate-500">Foto Siswa</p>
                                </div>
                                <div>
                                    <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.12em] text-blue-700">{{ $previewCard['kelas'] }}</span>
                                    <p class="mt-4 text-[40px] font-semibold leading-none text-slate-950">{{ $previewCard['nama'] }}</p>
                                    <dl class="mt-4 space-y-2 text-sm">
                                        <div class="grid grid-cols-[64px_1fr] gap-3"><dt class="font-semibold uppercase tracking-[0.12em] text-slate-500">NIS</dt><dd class="font-semibold text-slate-900">{{ $previewCard['nis'] }}</dd></div>
                                        <div class="grid grid-cols-[64px_1fr] gap-3"><dt class="font-semibold uppercase tracking-[0.12em] text-slate-500">Lahir</dt><dd class="font-semibold text-slate-900">{{ $previewCard['tanggal_lahir'] }}</dd></div>
                                        <div class="grid grid-cols-[64px_1fr] gap-3"><dt class="font-semibold uppercase tracking-[0.12em] text-slate-500">Gender</dt><dd class="font-semibold text-slate-900">{{ $previewCard['jenis_kelamin'] }}</dd></div>
                                        <div class="grid grid-cols-[64px_1fr] gap-3"><dt class="font-semibold uppercase tracking-[0.12em] text-slate-500">Alamat</dt><dd class="font-semibold text-slate-900">{{ $previewCard['alamat'] }}</dd></div>
                                    </dl>
                                </div>
                                <div class="text-center">
                                    <div class="rounded-[20px] border border-slate-200 bg-white p-2 shadow-sm">
                                        <div class="flex h-24 items-center justify-center rounded-xl bg-[linear-gradient(135deg,#0f172a_25%,#fff_25%,#fff_50%,#0f172a_50%,#0f172a_75%,#fff_75%,#fff_100%)] bg-[length:18px_18px]"></div>
                                    </div>
                                    <p class="mt-2 text-xs leading-5 text-slate-500">Scan QR di titik absensi.</p>
                                </div>
                            </div>
                            <div class="border-t border-slate-200 bg-slate-50 px-5 py-3 text-xs text-slate-500">Kartu operasional absensi harian.</div>
                        </div>
                    </div>
                    <div class="mt-4 flex flex-wrap gap-2">
                        <a href="{{ route('siswa.cards-pdf', ['per_page' => 1, 'show_address' => 1]) }}" target="_blank" class="rounded-2xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Dengan Alamat</a>
                        <a href="{{ route('siswa.cards-pdf', ['per_page' => 1, 'show_address' => 0]) }}" target="_blank" class="rounded-2xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Tanpa Alamat</a>
                    </div>
                </section>

                <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Preview PDF</p>
                            <h3 class="mt-2 text-lg font-semibold text-slate-900">8 siswa per halaman</h3>
                            <p class="mt-1 text-xs text-slate-500">Cocok untuk cetak massal. Nanti bisa dipadukan dengan filter kelas.</p>
                        </div>
                        <a href="{{ route('siswa.cards-pdf', ['per_page' => 8, 'show_address' => 1]) }}" target="_blank" class="rounded-2xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Cetak Kartu</a>
                    </div>

                    <div class="mt-5 grid grid-cols-2 gap-3 rounded-[26px] bg-slate-50 p-4">
                        @for($i = 0; $i < 4; $i++)
                            <div class="overflow-hidden rounded-[20px] border border-slate-200 bg-white shadow-sm">
                                <div class="bg-slate-900 px-3 py-2 text-white">
                                    <div class="flex items-center gap-2">
                                        <div class="flex h-8 w-8 items-center justify-center overflow-hidden rounded-xl bg-white text-[10px] font-semibold text-slate-900">
                                            @if($settings['school_logo_url'])
                                                <img src="{{ $settings['school_logo_url'] }}" alt="Logo sekolah" class="h-6 w-6 object-contain">
                                            @else
                                                {{ $schoolBranding['initials'] ?? 'AT' }}
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-[8px] uppercase tracking-[0.18em] text-slate-300">Kartu Absensi</p>
                                            <p class="text-sm font-semibold text-white">{{ $settings['school_name'] }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-[1fr_64px] gap-2 px-3 py-3">
                                    <div>
                                        <span class="inline-flex rounded-full bg-slate-200 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-[0.12em] text-slate-900">{{ $previewCard['kelas'] }}</span>
                                        <p class="mt-2 text-xl font-semibold leading-none text-slate-950">{{ $previewCard['nama'] }}</p>
                                        <p class="mt-2 text-[11px] font-semibold text-slate-600">NIS <span class="ml-2 text-slate-900">{{ $previewCard['nis'] }}</span></p>
                                        <p class="mt-1 text-[11px] font-semibold text-slate-600">Lahir <span class="ml-2 text-slate-900">{{ $previewCard['tanggal_lahir'] }}</span></p>
                                    </div>
                                    <div class="rounded-[16px] border border-slate-200 bg-white p-1.5">
                                        <div class="flex h-14 items-center justify-center rounded-lg bg-[linear-gradient(135deg,#0f172a_25%,#fff_25%,#fff_50%,#0f172a_50%,#0f172a_75%,#fff_75%,#fff_100%)] bg-[length:10px_10px]"></div>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                <div class="mt-4 flex flex-wrap gap-2">
                        <a href="{{ route('siswa.cards-pdf', ['per_page' => 8, 'show_address' => 1]) }}" target="_blank" class="rounded-2xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Dengan Alamat</a>
                        <a href="{{ route('siswa.cards-pdf', ['per_page' => 8, 'show_address' => 0]) }}" target="_blank" class="rounded-2xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Tanpa Alamat</a>
                    </div>
                </section>
            </aside>
        </div>
    </div>
</x-app-layout>
