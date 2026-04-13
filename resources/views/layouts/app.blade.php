<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-100 font-sans antialiased text-slate-900">
        <div x-data="{ sidebarOpen: false }" class="min-h-screen bg-slate-100 lg:flex">
            @include('layouts.navigation')

            <div class="flex-1 min-w-0">
                <div class="sticky top-0 z-30 border-b border-slate-200/80 bg-white/95 backdrop-blur lg:hidden">
                    <div class="flex items-center justify-between px-4 py-3">
                        <button @click="sidebarOpen = true" class="inline-flex items-center justify-center rounded-xl border border-slate-200 p-2 text-slate-500 transition hover:bg-slate-50 hover:text-slate-700">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        </button>
                        <div class="text-center">
                            <p class="text-sm font-semibold text-slate-900">{{ config('app.name', 'Laravel') }}</p>
                            <p class="text-xs text-slate-500">Sistem Absensi TK</p>
                        </div>
                        <div class="w-10"></div>
                    </div>
                </div>

                @isset($header)
                    <header class="border-b border-slate-200/80 bg-white/90 backdrop-blur">
                        <div class="px-4 py-5 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <main class="overflow-x-hidden">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
