<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SiswaController;

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JadwalPelajaranController;
use App\Http\Controllers\OrangTuaSiswaController;
use App\Http\Controllers\PortalOrangTuaController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\ParentCodeAuthController;

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/absensi', [AbsensiController::class,'index']);
    Route::get('/absensi/manual', [AbsensiController::class,'manualForm']);
    Route::post('/absensi/manual', [AbsensiController::class,'manualStore']);
    Route::get('/absensi/monitor', [AbsensiController::class,'monitor']);
    Route::get('/absensi/monitor/data', [AbsensiController::class,'monitorData']);
    Route::get('/absensi/rekap', [AbsensiController::class,'rekapSederhana']);
    Route::get('/absensi/riwayat', [AbsensiController::class,'riwayat']);
    Route::get('/absensi/riwayat/export', [AbsensiController::class,'exportRiwayat']);
    Route::post('/absensi/mark-alpha', [AbsensiController::class,'markAlpha']);
    Route::post('/absensi/unmark-alpha', [AbsensiController::class,'unmarkAlpha']);
    Route::post('/absensi/{absensi}/status', [AbsensiController::class,'updateStatus']);
    Route::post('/absensi/{absensi}/waktu', [AbsensiController::class,'updateWaktu']);
    Route::delete('/absensi/{absensi}', [AbsensiController::class,'destroy']);
    Route::post('/absensi/scan',[AbsensiController::class,'scan']);
    Route::post('/absensi/simpan',[AbsensiController::class,'simpan']);

    Route::resource('siswa', SiswaController::class);
    Route::get('/kelas', [KelasController::class, 'index']);
    Route::post('/kelas', [KelasController::class, 'store']);
    Route::patch('/kelas/{kelas}', [KelasController::class, 'update']);
    Route::delete('/kelas/{kelas}', [KelasController::class, 'destroy']);

    Route::get('/jadwal', [JadwalPelajaranController::class, 'index']);
    Route::post('/jadwal/mapel', [JadwalPelajaranController::class, 'storeMataPelajaran']);
    Route::patch('/jadwal/mapel/{mapel}', [JadwalPelajaranController::class, 'updateMataPelajaran']);
    Route::delete('/jadwal/mapel/{mapel}', [JadwalPelajaranController::class, 'destroyMataPelajaran']);
    Route::post('/jadwal', [JadwalPelajaranController::class, 'storeJadwal']);
    Route::patch('/jadwal/{jadwal}', [JadwalPelajaranController::class, 'updateJadwal']);
    Route::delete('/jadwal/{jadwal}', [JadwalPelajaranController::class, 'destroy']);
});

Route::view('/', 'welcome');

Route::middleware('guest')->group(function () {
    Route::get('/orang-tua/masuk', [ParentCodeAuthController::class, 'showForm']);
    Route::post('/orang-tua/masuk', [ParentCodeAuthController::class, 'login']);
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/dashboard/data', [DashboardController::class, 'data'])
    ->middleware(['auth', 'verified', 'role:admin']);

Route::middleware('auth')->group(function () {
    Route::middleware('role:admin')->group(function () {
        Route::get('/orang-tua/relasi', [OrangTuaSiswaController::class, 'index']);
        Route::post('/orang-tua/relasi', [OrangTuaSiswaController::class, 'store']);
        Route::delete('/orang-tua/relasi/{relasi}', [OrangTuaSiswaController::class, 'destroy']);
        Route::post('/orang-tua/kode/{user}', [OrangTuaSiswaController::class, 'generateCode']);
    });

    Route::middleware('role:orang_tua,admin')->group(function () {
        Route::get('/orang-tua/absensi-anak', [PortalOrangTuaController::class, 'index']);
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
