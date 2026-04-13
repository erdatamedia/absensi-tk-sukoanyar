<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\JadwalPelajaran;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $today = now()->toDateString();
        $kelasFilter = $request->input('kelas_id');
        $hariIni = $this->getHariIni();

        $stats = $this->buildStats($today);
        $kelasList = Kelas::orderBy('nama_kelas')->get();

        $anakHariIni = collect();
        $aktivitasTerbaru = collect();
        $jadwalHariIni = collect();

        if ($user->role === 'orang_tua') {
            $siswaIds = $user->relasiAnak()->pluck('siswa_id');
            $anakHariIni = $this->getAnakHariIni($today, $siswaIds);
            $jadwalHariIni = $this->getJadwalHariIniUntukOrangTua($hariIni, $siswaIds);
        } else {
            $aktivitasTerbaru = $this->getAktivitasTerbaru($today, $kelasFilter);
            $jadwalHariIni = $this->getJadwalHariIniUntukAdmin($hariIni, $kelasFilter);
        }

        return view('dashboard', [
            'today' => $today,
            'hariIni' => $hariIni,
            'jamSekarang' => now()->format('H:i:s'),
            'stats' => $stats,
            'anakHariIni' => $anakHariIni,
            'aktivitasTerbaru' => $aktivitasTerbaru,
            'jadwalHariIni' => $jadwalHariIni,
            'kelasList' => $kelasList,
            'kelasFilter' => $kelasFilter,
        ]);
    }

    public function data(Request $request): JsonResponse
    {
        $today = now()->toDateString();
        $kelasFilter = $request->input('kelas_id');
        $hariIni = $this->getHariIni();
        $jamSekarang = now()->format('H:i:s');

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

        $jadwalHariIni = $this->getJadwalHariIniUntukAdmin($hariIni, $kelasFilter)
            ->map(function ($item) use ($jamSekarang) {
                $isSedangBerlangsung = $jamSekarang >= $item->jam_mulai && $jamSekarang < $item->jam_selesai;
                $isAkanDatang = $jamSekarang < $item->jam_mulai;

                return [
                    'kelas' => $item->kelas->nama_kelas ?? '-',
                    'mata_pelajaran' => $item->mataPelajaran->nama ?? '-',
                    'jam_mulai' => (string) Str::of($item->jam_mulai)->substr(0, 5),
                    'jam_selesai' => (string) Str::of($item->jam_selesai)->substr(0, 5),
                    'kondisi' => $isSedangBerlangsung ? 'Sedang berlangsung' : ($isAkanDatang ? 'Akan datang' : 'Selesai'),
                    'is_active' => $isSedangBerlangsung,
                ];
            })
            ->values();

        return response()->json([
            'status' => 'ok',
            'jam_sekarang' => $jamSekarang,
            'stats' => $this->buildStats($today),
            'aktivitas_terbaru' => $aktivitasTerbaru,
            'jadwal_hari_ini' => $jadwalHariIni,
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

    private function getHariIni(): string
    {
        $dayMap = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu',
        ];

        return $dayMap[now()->dayOfWeekIso] ?? 'Senin';
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

    private function getJadwalHariIniUntukAdmin(string $hariIni, ?string $kelasFilter): Collection
    {
        return JadwalPelajaran::with(['kelas', 'mataPelajaran'])
            ->where('hari', $hariIni)
            ->when($kelasFilter, function ($query, $kelasFilter) {
                $query->where('kelas_id', $kelasFilter);
            })
            ->orderBy('kelas_id')
            ->orderBy('jam_mulai')
            ->get();
    }

    private function getJadwalHariIniUntukOrangTua(string $hariIni, Collection $siswaIds): Collection
    {
        $kelasIds = Siswa::whereIn('id', $siswaIds)
            ->pluck('kelas_id')
            ->unique()
            ->filter()
            ->values();

        if ($kelasIds->isEmpty()) {
            return collect();
        }

        return JadwalPelajaran::with(['kelas', 'mataPelajaran'])
            ->where('hari', $hariIni)
            ->whereIn('kelas_id', $kelasIds)
            ->orderBy('jam_mulai')
            ->get();
    }
}
