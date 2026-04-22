<x-app-layout>
    @php
        $operasionalMulai = \App\Support\Branding::operationalStart();
        $operasionalSelesai = \App\Support\Branding::operationalEnd();
        $jamSekarang = now()->format('H:i');
    @endphp

    <x-slot name="header">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Absensi</p>
                <h2 class="mt-1 text-2xl font-semibold leading-tight text-slate-900">Scan Absensi</h2>
                <p class="mt-1 text-sm text-slate-500">Pindai QR siswa, verifikasi identitas, lalu ambil selfie untuk menyimpan absensi.</p>
            </div>
            <div class="inline-flex items-center rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
                Operasional {{ $operasionalMulai }} - {{ $operasionalSelesai }}:
                <span class="ml-2 font-semibold text-slate-900">{{ $jamSekarang }} WIB</span>
            </div>
        </div>
    </x-slot>

    <div class="px-4 py-6 sm:px-6 lg:px-8">
        <div class="space-y-6">
            <section class="overflow-hidden rounded-[28px] bg-slate-900 px-6 py-6 text-white shadow-sm sm:px-8">
                <div class="grid gap-6 xl:grid-cols-[minmax(0,1.2fr)_minmax(300px,0.8fr)] xl:items-center">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-300">Panduan Cepat</p>
                        <h1 class="mt-3 text-3xl font-semibold leading-tight">Gunakan halaman ini untuk absensi masuk dan pulang siswa.</h1>
                        <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-300">
                            Guru cukup memindai QR siswa, memastikan nama yang muncul sudah benar, lalu menekan tombol selfie sesuai kebutuhan.
                            Foto yang diambil akan menjadi bukti absensi sekaligus dataset wajah.
                        </p>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-3 xl:grid-cols-1">
                        <div class="rounded-3xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                            <p class="text-sm text-slate-300">Langkah 1</p>
                            <p class="mt-2 text-lg font-semibold text-white">Scan QR</p>
                        </div>
                        <div class="rounded-3xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                            <p class="text-sm text-slate-300">Langkah 2</p>
                            <p class="mt-2 text-lg font-semibold text-white">Verifikasi Siswa</p>
                        </div>
                        <div class="rounded-3xl border border-emerald-400/20 bg-emerald-400/10 p-4">
                            <p class="text-sm text-emerald-100">Langkah 3</p>
                            <p class="mt-2 text-lg font-semibold text-white">Selfie dan Simpan</p>
                        </div>
                    </div>
                </div>
            </section>
            <section class="grid gap-6 xl:grid-cols-[minmax(0,1.2fr)_minmax(320px,0.8fr)]">
                <div class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">Kamera Absensi</h3>
                            <p class="mt-1 text-sm text-slate-500">Satu kamera dipakai untuk dua tahap: baca QR siswa lalu ambil selfie bukti hadir. Ini lebih stabil untuk laptop kiosk.</p>
                        </div>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-slate-600">Mode kiosk</span>
                    </div>

                    <div class="mt-5 overflow-hidden rounded-[24px] border border-slate-200 bg-slate-50 p-4">
                        <div id="reader" class="w-full overflow-hidden rounded-2xl border border-slate-200 bg-white"></div>
                    </div>

                    <div class="mt-4 flex flex-wrap gap-3">
                        <button id="btnStartScanner" type="button" class="rounded-2xl bg-slate-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-slate-800">Mulai Kamera</button>
                        <button id="btnRetryScanner" type="button" class="rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Muat Ulang Kamera</button>
                    </div>

                    <div class="mt-5 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-4">
                        <p class="text-sm font-medium text-slate-700">Status Kamera</p>
                        <p id="statusText" class="mt-2 text-sm text-slate-600">Arahkan QR siswa ke kamera.</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h3 class="text-base font-semibold text-slate-900">Hasil Verifikasi</h3>
                                <p class="mt-1 text-sm text-slate-500">Setelah QR terbaca, siswa tinggal melihat kamera yang sama lalu menekan tombol absensi.</p>
                            </div>
                            <span id="statusBadge" class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-slate-600">Menunggu scan</span>
                        </div>

                        <div class="mt-5 rounded-[24px] border border-slate-200 bg-slate-50 p-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Siswa</p>
                            <p id="namaSiswa" class="mt-2 text-xl font-semibold text-slate-900">Belum ada siswa terdeteksi</p>
                            <p id="cameraHint" class="mt-3 text-sm text-slate-500">Setelah QR valid, minta siswa melihat ke kamera untuk selfie bukti hadir.</p>
                            <p id="feedbackText" class="hidden mt-4 rounded-2xl border px-4 py-3 text-sm"></p>
                        </div>

                        <div id="actionButtons" class="hidden mt-5 flex flex-wrap gap-3">
                            <button id="btnMasuk" class="rounded-2xl bg-slate-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-slate-800">Ambil Selfie Masuk</button>
                            <button id="btnPulang" class="rounded-2xl bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-emerald-500">Ambil Selfie Pulang</button>
                            <button id="btnReset" type="button" class="rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Siswa Berikutnya</button>
                        </div>
                    </div>

                    <div class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                        <h3 class="text-base font-semibold text-slate-900">Alur Operator</h3>
                        <ol class="mt-4 space-y-3 text-sm text-slate-600">
                            <li>1. Guru membuka halaman ini dan menyalakan kamera.</li>
                            <li>2. Siswa datang bergantian, menunjukkan QR ke kamera.</li>
                            <li>3. Nama siswa tampil otomatis.</li>
                            <li>4. Siswa melihat kamera, lalu tekan tombol selfie sesuai kebutuhan.</li>
                            <li>5. Sistem menyimpan absensi dan foto dataset, lalu siap untuk siswa berikutnya.</li>
                        </ol>
                    </div>
                </div>
            </section>

            <canvas id="canvas" class="hidden"></canvas>

            <script>
                let scanned = false;
                let currentSiswaId = null;
                let scanner = null;
                let scannerRunning = false;
                let scannerPaused = false;

                const actionButtons = document.getElementById("actionButtons");
                const btnMasuk = document.getElementById("btnMasuk");
                const btnPulang = document.getElementById("btnPulang");
                const btnReset = document.getElementById("btnReset");
                const btnStartScanner = document.getElementById("btnStartScanner");
                const btnRetryScanner = document.getElementById("btnRetryScanner");
                const statusText = document.getElementById("statusText");
                const namaSiswa = document.getElementById("namaSiswa");
                const feedbackText = document.getElementById("feedbackText");
                const statusBadge = document.getElementById("statusBadge");
                const cameraHint = document.getElementById("cameraHint");
                let resetTimer = null;

                function setStatusBadge(message, type = "idle") {
                    const palette = {
                        idle: "bg-slate-100 text-slate-600",
                        info: "bg-blue-50 text-blue-700",
                        success: "bg-green-50 text-green-700",
                        warning: "bg-amber-50 text-amber-700",
                        error: "bg-red-50 text-red-700",
                    };

                    statusBadge.className = "rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wide " + (palette[type] || palette.idle);
                    statusBadge.innerText = message;
                }

                function setFeedback(message, type = "info") {
                    const palette = {
                        success: "border-green-200 bg-green-50 text-green-700",
                        error: "border-red-200 bg-red-50 text-red-700",
                        info: "border-blue-200 bg-blue-50 text-blue-700",
                    };

                    feedbackText.className = "mt-4 rounded-2xl border px-4 py-3 text-sm " + (palette[type] || palette.info);
                    feedbackText.innerText = message;
                    feedbackText.classList.remove("hidden");
                }

                function takePhoto() {
                    const video = document.querySelector("#reader video");
                    const canvas = document.getElementById("canvas");

                    if (!video.videoWidth || !video.videoHeight) return null;

                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;

                    const ctx = canvas.getContext("2d");
                    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

                    return canvas.toDataURL("image/jpeg", 0.85);
                }

                async function verifyQrToken(qrToken) {
                    const response = await fetch('/absensi/scan', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ qr_token: qrToken })
                    });

                    return response.json();
                }

                function describeCameraError(error) {
                    const name = error?.name || "";
                    if (name === "NotAllowedError" || name === "PermissionDeniedError") {
                        return "Izin kamera ditolak. Izinkan akses kamera di browser lalu coba lagi.";
                    }

                    if (name === "NotFoundError" || name === "DevicesNotFoundError") {
                        return "Perangkat tidak menemukan kamera yang bisa digunakan.";
                    }

                    if (name === "NotReadableError" || name === "TrackStartError") {
                        return "Kamera sedang dipakai aplikasi atau tab lain. Tutup yang lain lalu coba lagi.";
                    }

                    return error?.message || "Kamera tidak bisa diakses pada perangkat ini.";
                }

                async function stopQrScanner() {
                    if (!scanner || !scannerRunning) return;

                    try {
                        await scanner.stop();
                    } catch (error) {
                        console.error(error);
                    }

                    scannerRunning = false;
                }

                async function pauseScannerForSelfie() {
                    if (!scanner || !scannerRunning || scannerPaused) {
                        return;
                    }

                    try {
                        scanner.pause(false);
                        scannerPaused = true;
                    } catch (error) {
                        console.error(error);
                    }
                }

                async function resumeScannerAfterSelfie() {
                    if (!scanner || !scannerPaused) {
                        return;
                    }

                    try {
                        scanner.resume();
                        scannerPaused = false;
                    } catch (error) {
                        console.error(error);
                    }
                }

                async function saveAbsensi(jenis) {
                    btnMasuk.disabled = true;
                    btnPulang.disabled = true;
                    setStatusBadge("Menyimpan", "warning");
                    const buttonLoadingText = jenis === "masuk" ? "Menyimpan masuk..." : "Menyimpan pulang...";

                    if (jenis === "masuk") {
                        btnMasuk.innerText = buttonLoadingText;
                    } else {
                        btnPulang.innerText = buttonLoadingText;
                    }

                    try {
                        await pauseScannerForSelfie();
                        await new Promise(resolve => setTimeout(resolve, 400));
                        const foto = takePhoto();

                        if (!foto) {
                            throw new Error("Frame kamera belum siap untuk selfie. Arahkan wajah ke kamera lalu coba lagi.");
                        }

                        const response = await fetch('/absensi/simpan', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ siswa_id: currentSiswaId, foto, jenis })
                        });

                        const data = await response.json();
                        if (!response.ok || data.status !== 'ok') throw new Error(data.msg || 'Gagal simpan absensi');

                        if (data.jenis === "masuk") {
                            statusText.innerText = "Absensi masuk berhasil disimpan.";
                            setStatusBadge("Masuk tersimpan", "success");
                            setFeedback("Absensi masuk berhasil untuk " + data.nama + ".", "success");
                            cameraHint.innerText = "Absensi masuk tersimpan. Jika ini absensi pulang, siswa yang sama bisa lanjut tekan tombol pulang.";
                            btnMasuk.classList.add("hidden");
                            btnPulang.classList.remove("hidden");
                            btnPulang.disabled = false;
                        } else {
                            statusText.innerText = "Absensi pulang berhasil disimpan.";
                            setStatusBadge("Pulang tersimpan", "success");
                            setFeedback("Absensi pulang berhasil untuk " + data.nama + ".", "success");
                            cameraHint.innerText = "Data lengkap tersimpan. Sistem akan kembali siap untuk siswa berikutnya.";
                            resetTimer = setTimeout(() => {
                                resetScanState("Arahkan QR siswa berikutnya ke scanner.");
                            }, 1400);
                        }
                    } catch (err) {
                        const message = err.message || "Terjadi kesalahan saat menyimpan absensi.";
                        setStatusBadge("Gagal simpan", "error");
                        setFeedback(message, "error");
                        resetScanState("Arahkan QR siswa ke scanner.");
                        console.error(err);
                    } finally {
                        await resumeScannerAfterSelfie();
                        btnMasuk.disabled = false;
                        btnPulang.disabled = false;
                        btnMasuk.innerText = "Selfie Masuk";
                        btnPulang.innerText = "Selfie Pulang";
                    }
                }

                function resetScanState(message) {
                    scanned = false;
                    currentSiswaId = null;
                    namaSiswa.innerText = "Belum ada siswa terdeteksi";
                    feedbackText.classList.add("hidden");
                    feedbackText.innerText = "";
                    actionButtons.classList.add("hidden");
                    btnMasuk.classList.remove("hidden");
                    btnPulang.classList.remove("hidden");
                    cameraHint.innerText = "Setelah QR valid, minta siswa melihat ke kamera untuk selfie bukti hadir.";
                    statusText.innerText = message;
                    setStatusBadge("Menunggu scan", "idle");
                    if (resetTimer) {
                        clearTimeout(resetTimer);
                        resetTimer = null;
                    }
                    startQrScanner();
                }

                async function onScanSuccess(decodedText) {
                    if (scanned) return;

                    scanned = true;
                    statusText.innerText = "QR terbaca. Verifikasi siswa...";
                    setStatusBadge("Memverifikasi", "warning");

                    try {
                        const data = await verifyQrToken(decodedText);
                        if (data.status !== 'ok') throw new Error(data.msg || 'QR tidak dikenal');

                        await pauseScannerForSelfie();

                        currentSiswaId = data.siswa_id;
                        namaSiswa.innerText = "Siswa: " + data.nama;
                        actionButtons.classList.remove("hidden");

                        if (data.can_masuk) {
                            btnMasuk.classList.remove("hidden");
                        } else {
                            btnMasuk.classList.add("hidden");
                        }

                        if (data.can_pulang) {
                            btnPulang.classList.remove("hidden");
                        } else {
                            btnPulang.classList.add("hidden");
                        }

                        if (data.can_masuk && data.can_pulang) {
                            statusText.innerText = "Pilih aksi: masuk atau pulang.";
                            setStatusBadge("Pilih aksi", "info");
                            setFeedback("Siswa ditemukan. Silakan pilih aksi.", "info");
                        } else if (data.can_masuk) {
                            statusText.innerText = "Siswa belum absen masuk. Tekan Selfie Masuk.";
                            setStatusBadge("Siap masuk", "info");
                            cameraHint.innerText = "Minta siswa melihat ke kamera, lalu tekan Ambil Selfie Masuk.";
                            setFeedback("Siap selfie masuk untuk " + data.nama + ".", "info");
                        } else if (data.can_pulang) {
                            statusText.innerText = "Siswa sudah masuk. Tekan Selfie Pulang.";
                            setStatusBadge("Siap pulang", "info");
                            cameraHint.innerText = "Minta siswa melihat ke kamera, lalu tekan Ambil Selfie Pulang.";
                            setFeedback("Siap selfie pulang untuk " + data.nama + ".", "info");
                        } else {
                            statusText.innerText = "Absensi masuk & pulang hari ini sudah lengkap.";
                            actionButtons.classList.add("hidden");
                            setStatusBadge("Sudah lengkap", "success");
                            cameraHint.innerText = "Siswa ini sudah selesai absensi untuk hari ini.";
                            setFeedback("Absensi hari ini sudah lengkap. Scanner reset otomatis.", "info");
                            resetTimer = setTimeout(() => {
                                resetScanState("Arahkan QR siswa berikutnya ke scanner.");
                            }, 1800);
                        }
                    } catch (err) {
                        console.error(err);
                        resetScanState("Arahkan QR siswa ke scanner.");
                        setStatusBadge("QR tidak valid", "error");
                        setFeedback("QR tidak dikenal / gagal diverifikasi.", "error");
                    }
                }

                async function waitForScannerAsset(timeoutMs = 4000) {
                    const startedAt = Date.now();

                    while (Date.now() - startedAt < timeoutMs) {
                        if (typeof window.Html5Qrcode === "function") {
                            return true;
                        }

                        await new Promise(resolve => setTimeout(resolve, 100));
                    }

                    return typeof window.Html5Qrcode === "function";
                }

                async function startQrScanner() {
                    if (scannerRunning || scanned) {
                        return;
                    }

                    const scannerAssetReady = await waitForScannerAsset();

                    if (!scannerAssetReady) {
                        statusText.innerText = "Scanner QR belum siap dimuat.";
                        setStatusBadge("Scanner gagal dimuat", "error");
                        setFeedback("Asset scanner lokal tidak berhasil dimuat. Cek build frontend Vite.", "error");
                        return;
                    }

                    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                        statusText.innerText = "Browser tidak mendukung akses kamera.";
                        setStatusBadge("Browser tidak didukung", "error");
                        setFeedback("Gunakan Chrome, Edge, Safari, atau browser modern lain yang mendukung kamera.", "error");
                        return;
                    }

                    try {
                        if (!scanner) {
                            scanner = new window.Html5Qrcode("reader");
                        }

                        statusText.innerText = "Menyalakan kamera belakang...";
                        setStatusBadge("Menyalakan scanner", "warning");

                        const config = {
                            fps: 10,
                            qrbox: { width: 250, height: 250 },
                            aspectRatio: 1.333334,
                            rememberLastUsedCamera: true,
                        };

                        const cameras = await window.Html5Qrcode.getCameras();
                        let cameraConfig = undefined;

                        if (Array.isArray(cameras) && cameras.length > 0) {
                            cameraConfig = { deviceId: { exact: cameras[0].id } };
                        } else {
                            cameraConfig = { facingMode: "user" };
                        }

                        await scanner.start(
                            cameraConfig,
                            config,
                            onScanSuccess,
                            () => {}
                        );

                        scannerRunning = true;
                        scannerPaused = false;
                        statusText.innerText = "Scanner aktif. Arahkan QR siswa ke area scanner.";
                        setStatusBadge("Scanner aktif", "success");
                        cameraHint.innerText = "Setelah QR valid, minta siswa melihat ke kamera untuk selfie bukti hadir.";
                        feedbackText.classList.add("hidden");
                    } catch (error) {
                        console.error(error);
                        scannerRunning = false;
                        statusText.innerText = "Scanner QR tidak bisa dijalankan.";
                        setStatusBadge("Scanner gagal", "error");
                        setFeedback(describeCameraError(error), "error");
                    }
                }

                document.addEventListener("DOMContentLoaded", () => {
                    startQrScanner();

                    btnMasuk.addEventListener("click", () => saveAbsensi("masuk"));
                    btnPulang.addEventListener("click", () => saveAbsensi("pulang"));
                    btnReset.addEventListener("click", () => resetScanState("Arahkan QR siswa ke scanner."));
                    btnStartScanner.addEventListener("click", () => startQrScanner());
                    btnRetryScanner.addEventListener("click", async () => {
                        await stopQrScanner();
                        document.getElementById("reader").innerHTML = "";

                        if (typeof window.Html5Qrcode === "function") {
                            scanner = new window.Html5Qrcode("reader");
                            scannerPaused = false;
                        }

                        startQrScanner();
                    });
                });
            </script>
        </div>
    </div>
</x-app-layout>
