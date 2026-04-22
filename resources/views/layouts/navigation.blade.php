@php
    $isAdmin = auth()->user()->role === 'admin';
    $linkBase = 'group flex items-center gap-3 rounded-2xl px-3.5 py-3 text-sm font-medium transition';
    $active = 'bg-slate-900 text-white shadow-sm';
    $idle = 'text-slate-600 hover:bg-slate-100 hover:text-slate-900';
    $iconBase = 'h-5 w-5 shrink-0';

    $adminSections = [
        [
            'label' => 'Utama',
            'items' => [
                ['label' => 'Dashboard', 'href' => route('dashboard'), 'active' => request()->routeIs('dashboard'), 'icon' => 'home'],
            ],
        ],
        [
            'label' => 'Operasional Absensi',
            'items' => [
                ['label' => 'Scan Absensi', 'href' => url('/absensi'), 'active' => request()->is('absensi'), 'icon' => 'scan'],
                ['label' => 'Monitor Absensi', 'href' => url('/absensi/monitor'), 'active' => request()->is('absensi/monitor*'), 'icon' => 'pulse'],
                ['label' => 'Rekap Harian', 'href' => url('/absensi/rekap'), 'active' => request()->is('absensi/rekap'), 'icon' => 'clipboard'],
                ['label' => 'Riwayat Absensi', 'href' => url('/absensi/riwayat'), 'active' => request()->is('absensi/riwayat*'), 'icon' => 'history'],
            ],
        ],
        [
            'label' => 'Master Data',
            'items' => [
                ['label' => 'Data Siswa', 'href' => url('/siswa'), 'active' => request()->is('siswa*'), 'icon' => 'users'],
                ['label' => 'Data Kelas', 'href' => url('/kelas'), 'active' => request()->is('kelas*'), 'icon' => 'building'],
            ],
        ],
        [
            'label' => 'Akun',
            'items' => [
                ['label' => 'Profile', 'href' => route('profile.edit'), 'active' => request()->routeIs('profile.edit'), 'icon' => 'user'],
                ['label' => 'Pengaturan Sekolah', 'href' => route('settings.school.edit'), 'active' => request()->routeIs('settings.school.*'), 'icon' => 'building'],
            ],
        ],
    ];

    $parentSections = [
        [
            'label' => 'Akun',
            'items' => [
                ['label' => 'Dashboard', 'href' => route('dashboard'), 'active' => request()->routeIs('dashboard'), 'icon' => 'home'],
                ['label' => 'Profile', 'href' => route('profile.edit'), 'active' => request()->routeIs('profile.edit'), 'icon' => 'user'],
                ['label' => 'Pengaturan Sekolah', 'href' => route('settings.school.edit'), 'active' => request()->routeIs('settings.school.*'), 'icon' => 'building'],
            ],
        ],
    ];

    $sections = $isAdmin ? $adminSections : $parentSections;
@endphp

<div class="hidden lg:sticky lg:top-0 lg:flex lg:h-screen lg:w-72 lg:flex-col lg:border-r lg:border-slate-200 lg:bg-white">
    <div class="border-b border-slate-200 px-6 py-6">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <x-application-logo class="h-11 w-11" />
            <span>
                <span class="block text-sm font-semibold text-slate-900">{{ $schoolBranding['name'] ?? 'Absensi TK Sukoanyar' }}</span>
                <span class="mt-0.5 block text-xs text-slate-500">{{ $schoolBranding['tagline'] ?? 'Sistem Absensi TK' }}</span>
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
                            <x-sidebar-icon :name="$item['icon']" :class="$iconBase" />
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
                <x-application-logo class="h-10 w-10" />
                <div>
                    <p class="text-sm font-semibold text-slate-900">{{ $schoolBranding['name'] ?? 'Absensi TK Sukoanyar' }}</p>
                    <p class="text-xs text-slate-500">{{ $schoolBranding['tagline'] ?? 'Sistem Absensi TK' }}</p>
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
                                <x-sidebar-icon :name="$item['icon']" :class="$iconBase" />
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
