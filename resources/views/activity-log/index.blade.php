<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Audit</p>
                <h2 class="mt-1 text-2xl font-semibold leading-tight text-slate-900">Log Activity</h2>
                <p class="mt-1 text-sm text-slate-500">Catatan aksi penting sistem untuk membantu audit operasional harian.</p>
            </div>
            <div class="inline-flex items-center rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
                Total log: <span class="ml-2 font-semibold text-slate-900">{{ $logs->total() }}</span>
            </div>
        </div>
    </x-slot>

    <div class="px-4 py-6 sm:px-6 lg:px-8">
        <div class="space-y-6">
            @if($missingTable ?? false)
                <section class="rounded-[28px] border border-amber-200 bg-amber-50 p-6 text-sm text-amber-800 shadow-sm">
                    Tabel <code>activity_logs</code> belum ada. Jalankan <code>php artisan migrate</code> agar log activity aktif.
                </section>
            @endif

            <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                <form method="GET" action="/activity-log" class="grid gap-4 md:grid-cols-4">
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-slate-700">Cari aktivitas</label>
                        <input type="text" name="q" value="{{ $q }}" placeholder="Cari deskripsi, aksi, user, atau email" class="w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-slate-500 focus:ring-slate-500">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Aksi</label>
                        <select name="action" class="w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-slate-500 focus:ring-slate-500">
                            <option value="">Semua aksi</option>
                            @foreach($actions as $item)
                                <option value="{{ $item }}" {{ $actionFilter === $item ? 'selected' : '' }}>{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Per halaman</label>
                        <select name="per_page" class="w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-slate-500 focus:ring-slate-500">
                            <option value="20" {{ (string) $perPage === '20' ? 'selected' : '' }}>20</option>
                            <option value="50" {{ (string) $perPage === '50' ? 'selected' : '' }}>50</option>
                            <option value="100" {{ (string) $perPage === '100' ? 'selected' : '' }}>100</option>
                        </select>
                    </div>
                    <div class="md:col-span-4 flex justify-end">
                        <button type="submit" class="rounded-2xl bg-slate-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-slate-800">Terapkan Filter</button>
                    </div>
                </form>
            </section>

            <section class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50">
                            <tr class="text-left text-slate-500">
                                <th class="px-4 py-3 font-medium">Waktu</th>
                                <th class="px-4 py-3 font-medium">User</th>
                                <th class="px-4 py-3 font-medium">Aksi</th>
                                <th class="px-4 py-3 font-medium">Deskripsi</th>
                                <th class="px-4 py-3 font-medium">Subjek</th>
                                <th class="px-4 py-3 font-medium">IP</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse($logs as $log)
                                <tr class="align-top">
                                    <td class="px-4 py-3 text-slate-600">{{ $log->created_at?->format('Y-m-d H:i:s') ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-slate-900">{{ $log->user->name ?? 'System' }}</div>
                                        <div class="text-xs text-slate-500">{{ $log->user->email ?? '-' }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-slate-700">{{ $log->action }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-slate-700">{{ $log->description }}</td>
                                    <td class="px-4 py-3 text-slate-500">
                                        @if($log->subject_type)
                                            {{ class_basename($log->subject_type) }}#{{ $log->subject_id }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-slate-500">{{ $log->ip_address ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-slate-500">Belum ada log activity.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-slate-200 px-4 py-4">
                    {{ $logs->links() }}
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
