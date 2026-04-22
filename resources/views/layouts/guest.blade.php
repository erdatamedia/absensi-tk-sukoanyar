<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $schoolBranding['name'] ?? 'Absensi TK Sukoanyar' }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-100 font-sans text-slate-900 antialiased">
        <div class="relative min-h-screen overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(15,23,42,0.15),_transparent_35%),radial-gradient(circle_at_bottom_right,_rgba(14,165,233,0.12),_transparent_30%)]"></div>
            <div class="relative mx-auto flex min-h-screen max-w-6xl items-center px-4 py-8 sm:px-6 lg:px-8">
                <div class="grid w-full gap-6 lg:grid-cols-[minmax(0,1.15fr)_minmax(360px,440px)] lg:items-center">
                    <section class="rounded-[32px] bg-slate-900 px-8 py-10 text-white shadow-xl sm:px-10">
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-300">{{ $schoolBranding['tagline'] ?? 'Sistem Absensi TK' }}</p>
                        <h1 class="mt-4 max-w-xl text-4xl font-semibold leading-tight">Scan QR, ambil selfie, dan simpan rekap hadir dari satu titik absensi.</h1>
                        <p class="mt-4 max-w-2xl text-sm leading-7 text-slate-300">Aplikasi ini dipakai guru di laptop sekolah untuk absensi masuk dan pulang. Fokus sistem hanya pada operasional absensi, bukti selfie, dan rekap otomatis.</p>
                        <div class="mt-8 grid gap-4 sm:grid-cols-3">
                            <div class="rounded-3xl border border-white/10 bg-white/10 p-4">
                                <p class="text-xs uppercase tracking-[0.2em] text-slate-300">Langkah 1</p>
                                <p class="mt-2 text-sm font-medium text-white">Scan QR siswa</p>
                            </div>
                            <div class="rounded-3xl border border-white/10 bg-white/10 p-4">
                                <p class="text-xs uppercase tracking-[0.2em] text-slate-300">Langkah 2</p>
                                <p class="mt-2 text-sm font-medium text-white">Ambil selfie bukti hadir</p>
                            </div>
                            <div class="rounded-3xl border border-white/10 bg-white/10 p-4">
                                <p class="text-xs uppercase tracking-[0.2em] text-slate-300">Langkah 3</p>
                                <p class="mt-2 text-sm font-medium text-white">Rekap otomatis tersusun</p>
                            </div>
                        </div>
                    </section>

                    <section class="rounded-[32px] border border-slate-200 bg-white p-6 shadow-lg sm:p-8">
                        {{ $slot }}
                    </section>
                </div>
            </div>
        </div>
    </body>
</html>
