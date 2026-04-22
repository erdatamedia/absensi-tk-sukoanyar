<x-guest-layout>
    <div>
        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Akses Admin</p>
        <h2 class="mt-2 text-2xl font-semibold text-slate-900">Masuk ke sistem absensi</h2>
        <p class="mt-2 text-sm leading-6 text-slate-500">Gunakan akun admin sekolah. Tidak ada registrasi publik pada aplikasi ini.</p>
    </div>

    <x-auth-session-status class="mt-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-5">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" class="text-slate-700" />
            <x-text-input id="email" class="mt-2 block w-full rounded-2xl border-slate-300 px-4 py-3 shadow-sm focus:border-slate-900 focus:ring-0" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" class="text-slate-700" />
            <x-text-input id="password" class="mt-2 block w-full rounded-2xl border-slate-300 px-4 py-3 shadow-sm focus:border-slate-900 focus:ring-0" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <label for="remember_me" class="flex items-center gap-3 rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-600">
            <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-slate-900 shadow-sm focus:ring-slate-900" name="remember">
            <span>Tetap masuk di perangkat ini</span>
        </label>

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            @if (Route::has('password.request'))
                <a class="text-sm text-slate-500 underline-offset-4 transition hover:text-slate-900 hover:underline" href="{{ route('password.request') }}">
                    Lupa password?
                </a>
            @endif

            <x-primary-button class="justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-medium text-white shadow-sm hover:bg-slate-800 focus:bg-slate-800 active:bg-slate-900">
                Masuk
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
