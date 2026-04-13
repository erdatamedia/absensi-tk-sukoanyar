<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Absensi</p>
                <h2 class="mt-1 text-2xl font-semibold leading-tight text-slate-900">Monitor Absensi</h2>
                <p class="mt-1 text-sm text-slate-500">Pantau perubahan absensi hari ini secara realtime dari satu tampilan.</p>
            </div>
            <div class="inline-flex items-center rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
                Tanggal aktif: <span class="ml-2 font-semibold text-slate-900">{{ $today }}</span>
            </div>
        </div>
    </x-slot>

    <div class="px-4 py-6 sm:px-6 lg:px-8">
        <div class="space-y-6">
            <section class="overflow-hidden rounded-[28px] bg-slate-900 px-6 py-6 text-white shadow-sm sm:px-8">
                <div class="grid gap-6 xl:grid-cols-[minmax(0,1.2fr)_minmax(320px,0.8fr)] xl:items-center">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-300">Realtime Monitor</p>
                        <h1 class="mt-3 text-3xl font-semibold leading-tight">Lihat pergerakan absensi masuk, pulang, dan alpha sepanjang hari.</h1>
                        <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-300">
                            Halaman ini mengambil pembaruan berkala dari server untuk membantu admin memantau aktivitas absensi terbaru
                            tanpa perlu me-refresh halaman secara manual.
                        </p>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-1">
                        <div class="rounded-3xl border border-white/10 bg-white/10 p-5 backdrop-blur">
                            <p class="text-sm text-slate-300">Mode Monitor</p>
                            <p class="mt-3 text-2xl font-semibold text-white">Realtime 15 detik</p>
                            <p class="mt-2 text-sm text-slate-300">Data diperbarui otomatis dari endpoint monitor.</p>
                        </div>
                        <div class="rounded-3xl border border-emerald-400/20 bg-emerald-400/10 p-5">
                            <p class="text-sm text-emerald-100">Update Server</p>
                            <p id="lastUpdated" class="mt-3 text-lg font-semibold text-white">Menunggu pembaruan pertama</p>
                            <p class="mt-2 text-sm text-emerald-100/90">Status ini berubah saat data terbaru diterima.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="flex flex-wrap gap-3 text-sm text-slate-600">
                <a href="/absensi" class="rounded-full border border-slate-200 bg-white px-4 py-2 transition hover:bg-slate-50">Scan Absensi</a>
                <a href="/absensi/riwayat" class="rounded-full border border-slate-200 bg-white px-4 py-2 transition hover:bg-slate-50">Riwayat Absensi</a>
                <a href="/absensi/manual" class="rounded-full border border-slate-200 bg-white px-4 py-2 transition hover:bg-slate-50">Input Manual</a>
            </section>

            <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-[24px] border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-slate-500">Total Masuk</p>
                    <p id="sumMasuk" class="mt-4 text-3xl font-semibold text-slate-900">{{ $summary['masuk'] }}</p>
                    <p class="mt-2 text-sm text-slate-500">Jumlah absensi yang sudah tercatat hari ini.</p>
                </div>
                <div class="rounded-[24px] border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-slate-500">Total Pulang</p>
                    <p id="sumPulang" class="mt-4 text-3xl font-semibold text-slate-900">{{ $summary['pulang'] }}</p>
                    <p class="mt-2 text-sm text-slate-500">Siswa yang sudah menyelesaikan absensi pulang.</p>
                </div>
                <div class="rounded-[24px] border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-slate-500">Belum Pulang</p>
                    <p id="sumBelumPulang" class="mt-4 text-3xl font-semibold text-slate-900">{{ $summary['belum_pulang'] }}</p>
                    <p class="mt-2 text-sm text-slate-500">Siswa yang sudah masuk tetapi belum pulang.</p>
                </div>
                <div class="rounded-[24px] border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-slate-500">Status Alpha</p>
                    <p id="sumAlpha" class="mt-4 text-3xl font-semibold text-slate-900">{{ $summary['alpha'] }}</p>
                    <p class="mt-2 text-sm text-slate-500">Jumlah siswa dengan status alpha pada tanggal ini.</p>
                </div>
            </section>

            <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Aktivitas Terbaru</h3>
                        <p class="mt-1 text-sm text-slate-500">Urutan data di bawah ini selalu mengikuti perubahan terbaru pada absensi hari ini.</p>
                    </div>
                    <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-slate-600">Auto refresh aktif</span>
                </div>

                <div class="mt-5 w-full max-w-full overflow-x-auto rounded-2xl border border-slate-200">
                    <table class="w-full min-w-[1200px] divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50 text-slate-700">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold">No</th>
                                <th class="px-4 py-3 text-left font-semibold">Waktu Update</th>
                                <th class="px-4 py-3 text-left font-semibold">NIS</th>
                                <th class="px-4 py-3 text-left font-semibold">Nama</th>
                                <th class="px-4 py-3 text-left font-semibold">Kelas</th>
                                <th class="px-4 py-3 text-left font-semibold">Jam Masuk</th>
                                <th class="px-4 py-3 text-left font-semibold">Jam Pulang</th>
                                <th class="px-4 py-3 text-left font-semibold">Status</th>
                                <th class="px-4 py-3 text-left font-semibold">Keterangan</th>
                                <th class="px-4 py-3 text-left font-semibold">Sumber</th>
                                <th class="px-4 py-3 text-left font-semibold">Terlambat</th>
                            </tr>
                        </thead>
                        <tbody id="monitorRows" class="divide-y divide-slate-100 bg-white">
                            @forelse($aktivitasTerbaru as $item)
                                <tr>
                                    <td class="px-4 py-3 text-slate-700">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3 text-slate-700">{{ $item->updated_at }}</td>
                                    <td class="px-4 py-3 text-slate-700">{{ $item->siswa->nis ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-700">{{ $item->siswa->nama ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-700">{{ $item->siswa->kelas->nama_kelas ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-700">{{ $item->jam_masuk ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-700">{{ $item->jam_pulang ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex rounded-full border px-2.5 py-1 text-xs font-medium {{ $item->status === 'hadir' ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : ($item->status === 'alpha' ? 'border-rose-200 bg-rose-50 text-rose-700' : ($item->status === 'izin' ? 'border-blue-200 bg-blue-50 text-blue-700' : 'border-amber-200 bg-amber-50 text-amber-700')) }}">
                                            {{ $item->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-slate-700">{{ $item->keterangan ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-700">{{ $item->sumber ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex rounded-full border px-2.5 py-1 text-xs font-medium {{ $item->terlambat ? 'border-amber-200 bg-amber-50 text-amber-700' : 'border-slate-200 bg-slate-100 text-slate-600' }}">
                                            {{ $item->terlambat ? 'ya' : 'tidak' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="px-4 py-8 text-center text-slate-500">Belum ada aktivitas absensi hari ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <script>
                const monitorDate = "{{ $today }}";
                const monitorEndpoint = "/absensi/monitor/data?tanggal=" + encodeURIComponent(monitorDate) + "&limit=25";

                function escapeHtml(value) {
                    return String(value)
                        .replaceAll("&", "&amp;")
                        .replaceAll("<", "&lt;")
                        .replaceAll(">", "&gt;")
                        .replaceAll('"', "&quot;")
                        .replaceAll("'", "&#039;");
                }

                function statusBadgeClass(status) {
                    if (status === "hadir") return "border-emerald-200 bg-emerald-50 text-emerald-700";
                    if (status === "alpha") return "border-rose-200 bg-rose-50 text-rose-700";
                    if (status === "izin") return "border-blue-200 bg-blue-50 text-blue-700";
                    return "border-amber-200 bg-amber-50 text-amber-700";
                }

                function terlambatBadgeClass(isTerlambat) {
                    return isTerlambat
                        ? "border-amber-200 bg-amber-50 text-amber-700"
                        : "border-slate-200 bg-slate-100 text-slate-600";
                }

                function renderRows(items) {
                    const tbody = document.getElementById("monitorRows");

                    if (!items.length) {
                        tbody.innerHTML = '<tr><td colspan="11" class="px-4 py-8 text-center text-slate-500">Belum ada aktivitas absensi hari ini.</td></tr>';
                        return;
                    }

                    tbody.innerHTML = items.map((item, index) => `
                        <tr class="border-t border-slate-100">
                            <td class="px-4 py-3 text-slate-700">${index + 1}</td>
                            <td class="px-4 py-3 text-slate-700">${escapeHtml(item.updated_at)}</td>
                            <td class="px-4 py-3 text-slate-700">${escapeHtml(item.nis)}</td>
                            <td class="px-4 py-3 text-slate-700">${escapeHtml(item.nama)}</td>
                            <td class="px-4 py-3 text-slate-700">${escapeHtml(item.kelas)}</td>
                            <td class="px-4 py-3 text-slate-700">${escapeHtml(item.jam_masuk)}</td>
                            <td class="px-4 py-3 text-slate-700">${escapeHtml(item.jam_pulang)}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full border px-2.5 py-1 text-xs font-medium ${statusBadgeClass(item.status_absensi)}">
                                    ${escapeHtml(item.status_absensi)}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-slate-700">${escapeHtml(item.keterangan || "-")}</td>
                            <td class="px-4 py-3 text-slate-700">${escapeHtml(item.sumber || "-")}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full border px-2.5 py-1 text-xs font-medium ${terlambatBadgeClass(item.terlambat)}">
                                    ${item.terlambat ? "ya" : "tidak"}
                                </span>
                            </td>
                        </tr>
                    `).join("");
                }

                async function refreshMonitorData() {
                    try {
                        const response = await fetch(monitorEndpoint, { headers: { "Accept": "application/json" } });
                        if (!response.ok) return;

                        const data = await response.json();
                        if (data.status !== "ok") return;

                        document.getElementById("sumMasuk").innerText = data.summary.masuk;
                        document.getElementById("sumPulang").innerText = data.summary.pulang;
                        document.getElementById("sumBelumPulang").innerText = data.summary.belum_pulang;
                        document.getElementById("sumAlpha").innerText = data.summary.alpha;
                        document.getElementById("lastUpdated").innerText = data.server_time;

                        renderRows(data.aktivitas || []);
                    } catch (err) {
                        console.error("Gagal update monitor realtime", err);
                    }
                }

                setInterval(refreshMonitorData, 15000);
            </script>
        </div>
    </div>
</x-app-layout>
