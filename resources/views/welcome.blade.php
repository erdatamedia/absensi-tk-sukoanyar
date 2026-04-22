<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $schoolBranding['name'] ?? 'Absensi TK Sukoanyar' }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-100 font-sans text-slate-900 antialiased">
        <div class="relative min-h-screen overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(15,23,42,0.18),_transparent_35%),radial-gradient(circle_at_bottom_right,_rgba(14,165,233,0.14),_transparent_28%)]"></div>
            <div class="relative mx-auto flex min-h-screen max-w-6xl items-center px-4 py-8 sm:px-6 lg:px-8">
                <div class="grid w-full gap-6 lg:grid-cols-[minmax(0,1.2fr)_380px] lg:items-center">
                    <section class="rounded-[36px] bg-slate-900 px-8 py-10 text-white shadow-xl sm:px-10 sm:py-12">
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-300">{{ $schoolBranding['name'] ?? 'Absensi TK Sukoanyar' }}</p>
                        <h1 class="mt-4 max-w-2xl text-4xl font-semibold leading-tight">Satu laptop di titik absensi untuk scan QR, selfie bukti hadir, dan rekap otomatis.</h1>
                        <p class="mt-4 max-w-2xl text-sm leading-7 text-slate-300">Guru membuka sistem di laptop sekolah, siswa datang bergantian dari kelas TK A atau TK B, scan kartu QR, lalu ambil selfie. Data hadir masuk dan pulang langsung tercatat ke rekap harian.</p>

                        <div class="mt-8 grid gap-4 sm:grid-cols-3">
                            <div class="rounded-3xl border border-white/10 bg-white/10 p-5">
                                <p class="text-xs uppercase tracking-[0.22em] text-slate-300">Scan</p>
                                <p class="mt-2 text-sm font-medium text-white">QR siswa dikenali otomatis.</p>
                            </div>
                            <div class="rounded-3xl border border-white/10 bg-white/10 p-5">
                                <p class="text-xs uppercase tracking-[0.22em] text-slate-300">Selfie</p>
                                <p class="mt-2 text-sm font-medium text-white">Foto hadir tersimpan sebagai bukti dan dataset.</p>
                            </div>
                            <div class="rounded-3xl border border-white/10 bg-white/10 p-5">
                                <p class="text-xs uppercase tracking-[0.22em] text-slate-300">Rekap</p>
                                <p class="mt-2 text-sm font-medium text-white">Monitor, rekap, dan riwayat hadir tersusun otomatis.</p>
                            </div>
                        </div>
                    </section>

                    <aside class="rounded-[36px] border border-slate-200 bg-white p-6 shadow-lg sm:p-8">
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Akses Sistem</p>
                        <h2 class="mt-2 text-2xl font-semibold text-slate-900">Masuk sebagai admin sekolah</h2>
                        <p class="mt-2 text-sm leading-6 text-slate-500">Halaman ini hanya menyediakan akses masuk. Registrasi publik tidak tersedia.</p>

                        <div class="mt-6 space-y-3">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="inline-flex w-full items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-medium text-white transition hover:bg-slate-800">Buka Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="inline-flex w-full items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-medium text-white transition hover:bg-slate-800">Masuk ke Sistem</a>
                            @endauth
                        </div>

                        <div class="mt-6 rounded-3xl border border-slate-200 bg-slate-50 p-5">
                            <p class="text-sm font-semibold text-slate-900">Flow operasional</p>
                            <ol class="mt-3 space-y-2 text-sm text-slate-600">
                                <li>1. Guru membuka halaman scan di laptop sekolah.</li>
                                <li>2. Siswa scan kartu QR di titik absensi.</li>
                                <li>3. Guru atau operator menekan ambil selfie.</li>
                                <li>4. Sistem simpan hadir dan perbarui rekap otomatis.</li>
                            </ol>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </body>
</html>
