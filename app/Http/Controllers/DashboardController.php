<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $today = now()->toDateString();
        $kelasFilter = $request->input('kelas_id');

        $stats = $this->buildStats($today);
        $kelasList = Kelas::orderBy('nama_kelas')->get();

        $anakHariIni = collect();
        $aktivitasTerbaru = collect();

        if ($user->role === 'orang_tua') {
            $siswaIds = $user->relasiAnak()->pluck('siswa_id');
            $anakHariIni = $this->getAnakHariIni($today, $siswaIds);
        } else {
            $aktivitasTerbaru = $this->getAktivitasTerbaru($today, $kelasFilter);
        }

        return view('dashboard', [
            'today' => $today,
            'stats' => $stats,
            'anakHariIni' => $anakHariIni,
            'aktivitasTerbaru' => $aktivitasTerbaru,
            'kelasList' => $kelasList,
            'kelasFilter' => $kelasFilter,
        ]);
    }

    public function data(Request $request): JsonResponse
    {
        $today = now()->toDateString();
        $kelasFilter = $request->input('kelas_id');

        $aktivitasTerbaru = $this->getAktivitasTerbaru($today, $kelasFilter)
            ->map(function ($item) {
                return [
                    'updated_at' => (string) $item->updated_at,
                    'nama' => $item->siswa->nama ?? '-',
                    'kelas' => $item->siswa->kelas->nama_kelas ?? '-',
                    'jam_masuk' => $item->jam_masuk ?? '-',
                    'jam_pulang' => $item->jam_pulang ?? '-',
                    'status' => $item->status,
                ];
            })
            ->values();

        return response()->json([
            'status' => 'ok',
            'stats' => $this->buildStats($today),
            'aktivitas_terbaru' => $aktivitasTerbaru,
        ]);
    }

    private function buildStats(string $today): array
    {
        $stats = [
            'total_siswa' => Siswa::count(),
            'total_kelas' => Kelas::count(),
            'masuk_hari_ini' => Absensi::whereDate('tanggal', $today)->whereNotNull('jam_masuk')->count(),
            'pulang_hari_ini' => Absensi::whereDate('tanggal', $today)->whereNotNull('jam_pulang')->count(),
        ];

        $stats['belum_pulang_hari_ini'] = max($stats['masuk_hari_ini'] - $stats['pulang_hari_ini'], 0);
        $stats['status_hari_ini'] = [
            'hadir' => Absensi::whereDate('tanggal', $today)->where('status', 'hadir')->count(),
            'izin' => Absensi::whereDate('tanggal', $today)->where('status', 'izin')->count(),
            'sakit' => Absensi::whereDate('tanggal', $today)->where('status', 'sakit')->count(),
            'alpha' => Absensi::whereDate('tanggal', $today)->where('status', 'alpha')->count(),
        ];

        return $stats;
    }

    private function getAktivitasTerbaru(string $today, ?string $kelasFilter): Collection
    {
        return Absensi::with('siswa.kelas')
            ->whereDate('tanggal', $today)
            ->when($kelasFilter, function ($query, $kelasFilter) {
                $query->whereHas('siswa', function ($siswaQuery) use ($kelasFilter) {
                    $siswaQuery->where('kelas_id', $kelasFilter);
                });
            })
            ->orderByDesc('updated_at')
            ->limit(5)
            ->get();
    }

    private function getAnakHariIni(string $today, Collection $siswaIds): Collection
    {
        return Absensi::with('siswa.kelas')
            ->whereIn('siswa_id', $siswaIds)
            ->whereDate('tanggal', $today)
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();
    }
}
