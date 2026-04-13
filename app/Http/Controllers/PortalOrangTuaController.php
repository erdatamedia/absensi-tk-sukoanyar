<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;

class PortalOrangTuaController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $tanggalDari = $request->input('tanggal_dari');
        $tanggalSampai = $request->input('tanggal_sampai');

        $anakIds = $user->relasiAnak()->pluck('siswa_id');

        $absensiAnak = Absensi::with(['siswa.kelas'])
            ->whereIn('siswa_id', $anakIds)
            ->when($tanggalDari, function ($query, $tanggalDari) {
                $query->whereDate('tanggal', '>=', $tanggalDari);
            })
            ->when($tanggalSampai, function ($query, $tanggalSampai) {
                $query->whereDate('tanggal', '<=', $tanggalSampai);
            })
            ->orderByDesc('tanggal')
            ->orderByDesc('jam_masuk')
            ->paginate(20)
            ->withQueryString();

        return view('orangtua.absensi-anak', [
            'absensiAnak' => $absensiAnak,
            'tanggalDari' => $tanggalDari,
            'tanggalSampai' => $tanggalSampai,
        ]);
    }
}
