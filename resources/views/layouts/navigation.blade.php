@php
    $isAdmin = auth()->user()->role === 'admin';
    $linkBase = 'group flex items-center gap-3 rounded-2xl px-3.5 py-3 text-sm font-medium transition';
    $active = 'bg-slate-900 text-white shadow-sm';
    $idle = 'text-slate-600 hover:bg-slate-100 hover:text-slate-900';
    $iconBase = 'h-5 w-5 shrink-0';

    $adminSections = [
        [
            'label' => 'Overview',
            'items' => [
                ['label' => 'Dashboard', 'href' => route('dashboard'), 'active' => request()->routeIs('dashboard'), 'icon' => 'home'],
            ],
        ],
        [
            'label' => 'Absensi',
            'items' => [
                ['label' => 'Scan Absensi', 'href' => url('/absensi'), 'active' => request()->is('absensi'), 'icon' => 'scan'],
                ['label' => 'Rekap Sederhana', 'href' => url('/absensi/rekap'), 'active' => request()->is('absensi/rekap'), 'icon' => 'clipboard'],
                ['label' => 'Monitor Absensi', 'href' => url('/absensi/monitor'), 'active' => request()->is('absensi/monitor*'), 'icon' => 'pulse'],
                ['label' => 'Riwayat Absensi', 'href' => url('/absensi/riwayat'), 'active' => request()->is('absensi/riwayat*'), 'icon' => 'history'],
            ],
        ],
        [
            'label' => 'Master Data',
            'items' => [
                ['label' => 'Data Siswa', 'href' => url('/siswa'), 'active' => request()->is('siswa*'), 'icon' => 'users'],
                ['label' => 'Data Kelas', 'href' => url('/kelas'), 'active' => request()->is('kelas*'), 'icon' => 'building'],
                ['label' => 'Jadwal', 'href' => url('/jadwal'), 'active' => request()->is('jadwal*'), 'icon' => 'calendar'],
            ],
        ],
        [
            'label' => 'Portal',
            'items' => [
                ['label' => 'Relasi Orang Tua', 'href' => url('/orang-tua/relasi'), 'active' => request()->is('orang-tua/relasi*'), 'icon' => 'link'],
                ['label' => 'Profile', 'href' => route('profile.edit'), 'active' => request()->routeIs('profile.edit'), 'icon' => 'user'],
            ],
        ],
    ];

    $parentSections = [
        [
            'label' => 'Overview',
            'items' => [
                ['label' => 'Dashboard', 'href' => route('dashboard'), 'active' => request()->routeIs('dashboard'), 'icon' => 'home'],
                ['label' => 'Absensi Anak', 'href' => url('/orang-tua/absensi-anak'), 'active' => request()->is('orang-tua/absensi-anak*'), 'icon' => 'clipboard'],
                ['label' => 'Profile', 'href' => route('profile.edit'), 'active' => request()->routeIs('profile.edit'), 'icon' => 'user'],
            ],
        ],
    ];

    $sections = $isAdmin ? $adminSections : $parentSections;
@endphp

@once
    @php
        function sidebarIcon(string $icon, string $classes): string
        {
            return match ($icon) {
                'home' => '<svg class="'.$classes.'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 10.75 12 3l9 7.75V20a1 1 0 0 1-1 1h-5.5v-6h-5v6H4a1 1 0 0 1-1-1v-9.25Z"/></svg>',
                'scan' => '<svg class="'.$classes.'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 4H5a1 1 0 0 0-1 1v2m13-3h2a1 1 0 0 1 1 1v2M4 17v2a1 1 0 0 0 1 1h2m13-3v2a1 1 0 0 1-1 1h-2M7 12h10"/></svg>',
                'clipboard' => '<svg class="'.$classes.'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 4.5h6m-7 3H7a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2h-1m-8-3h8a1 1 0 0 1 1 1v2H8v-2a1 1 0 0 1 1-1Z"/></svg>',
                'pulse' => '<svg class="'.$classes.'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12h4l2-5 4 10 2-5h6"/></svg>',
                'history' => '<svg class="'.$classes.'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 12a8 8 0 1 0 2.34-5.66M4 4v4h4m4-1v5l3 2"/></svg>',
                'users' => '<svg class="'.$classes.'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2m18 0v-2a4 4 0 0 0-3-3.87M14 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0Zm7 2a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>',
                'building' => '<svg class="'.$classes.'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 21h16M7 21V7l5-3 5 3v14M9 10h.01M9 13h.01M9 16h.01M15 10h.01M15 13h.01M15 16h.01"/></svg>',
                'calendar' => '<svg class="'.$classes.'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 3v3m8-3v3M4 9h16M5 5h14a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z"/></svg>',
                'link' => '<svg class="'.$classes.'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 13a5 5 0 0 0 7.07 0l1.41-1.41a5 5 0 1 0-7.07-7.07L10 5m4 6a5 5 0 0 0-7.07 0l-1.41 1.41a5 5 0 0 0 7.07 7.07L14 19"/></svg>',
                'user' => '<svg class="'.$classes.'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4Zm7 9a7 7 0 0 0-14 0"/></svg>',
                default => '<svg class="'.$classes.'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="8" stroke-width="1.8"/></svg>',
            };
        }
    @endphp
@endonce

<div class="hidden lg:sticky lg:top-0 lg:flex lg:h-screen lg:w-72 lg:flex-col lg:border-r lg:border-slate-200 lg:bg-white">
    <div class="border-b border-slate-200 px-6 py-6">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-900 text-sm font-semibold text-white">AT</span>
            <span>
                <span class="block text-sm font-semibold text-slate-900">{{ config('app.name', 'Laravel') }}</span>
                <span class="mt-0.5 block text-xs text-slate-500">Sistem Absensi TK</span>
            </span>
        </a>
    </div>

    <nav class="flex-1 space-y-6 overflow-y-auto px-4 py-6">
        @foreach($sections as $section)
            <div>
                <p class="px-3.5 text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">{{ $section['label'] }}</p>
                <div class="mt-2 space-y-1.5">
                    @foreach($section['items'] as $item)
                        <a href="{{ $item['href'] }}" class="{{ $linkBase }} {{ $item['active'] ? $active : $idle }}">
                            {!! sidebarIcon($item['icon'], $iconBase) !!}
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </nav>

    <div class="border-t border-slate-200 p-4">
        <div class="rounded-2xl bg-slate-50 p-4">
            <div class="mb-4 flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-900 text-sm font-semibold text-white">
                    {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr(Auth::user()->name, 0, 1)) }}
                </span>
                <div class="min-w-0">
                    <p class="truncate text-sm font-semibold text-slate-900">{{ Auth::user()->name }}</p>
                    <p class="truncate text-xs text-slate-500">{{ Auth::user()->email }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-100">Log Out</button>
            </form>
        </div>
    </div>
</div>

<div class="lg:hidden" x-show="sidebarOpen" x-transition.opacity>
    <div class="fixed inset-0 z-40 bg-black/30" @click="sidebarOpen = false"></div>

    <aside class="fixed inset-y-0 left-0 z-50 w-72 bg-white shadow-xl">
        <div class="flex items-center justify-between border-b border-slate-200 px-5 py-5">
            <div class="flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-900 text-sm font-semibold text-white">AT</span>
                <div>
                    <p class="text-sm font-semibold text-slate-900">{{ config('app.name', 'Laravel') }}</p>
                    <p class="text-xs text-slate-500">Sistem Absensi TK</p>
                </div>
            </div>
            <button @click="sidebarOpen = false" class="rounded-xl p-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-700">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <nav class="h-[calc(100%-180px)] space-y-6 overflow-y-auto px-4 py-6">
            @foreach($sections as $section)
                <div>
                    <p class="px-3.5 text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">{{ $section['label'] }}</p>
                    <div class="mt-2 space-y-1.5">
                        @foreach($section['items'] as $item)
                            <a href="{{ $item['href'] }}" @click="sidebarOpen = false" class="{{ $linkBase }} {{ $item['active'] ? $active : $idle }}">
                                {!! sidebarIcon($item['icon'], $iconBase) !!}
                                <span>{{ $item['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </nav>

        <div class="absolute inset-x-0 bottom-0 border-t border-slate-200 bg-white p-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-100">Log Out</button>
            </form>
        </div>
    </aside>
</div>
