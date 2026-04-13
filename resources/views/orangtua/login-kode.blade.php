<x-guest-layout>
    <div class="space-y-4">
        <h2 class="text-lg font-semibold text-gray-900">Login Orang Tua dengan Kode</h2>
        <p class="text-sm text-gray-600">Masukkan kode akses yang diberikan admin.</p>

        @if(session('error'))
            <p class="rounded-md border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">{{ session('error') }}</p>
        @endif

        <form method="POST" action="/orang-tua/masuk" class="space-y-3">
            @csrf
            <div>
                <label for="kode" class="mb-1 block text-sm font-medium text-gray-700">Kode Akses</label>
                <input id="kode" type="text" name="kode" placeholder="Contoh: A1B2C3" required maxlength="12" class="block w-full rounded-md border-gray-300 uppercase text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <button type="submit" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500">Masuk</button>
        </form>
    </div>
</x-guest-layout>
