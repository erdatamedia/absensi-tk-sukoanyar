<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Absensi;
use App\Models\Kelas;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class AbsensiController extends Controller
{
    public function index()
    {
        return view('absensi.scan');
    }

    public function manualForm(Request $request)
    {
        $tanggal = $request->input('tanggal', now()->toDateString());
        $kelasId = $request->input('kelas_id');
        $cutoffMasuk = $this->getMasukCutoff();

        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $siswaList = Siswa::with('kelas')
            ->when($kelasId, function ($query, $kelasId) {
                $query->where('kelas_id', $kelasId);
            })
            ->orderBy('nama')
            ->get();

        return view('absensi.manual', [
            'tanggal' => $tanggal,
            'kelasId' => $kelasId,
            'kelasList' => $kelasList,
            'siswaList' => $siswaList,
            'cutoffMasuk' => $cutoffMasuk,
        ]);
    }

    public function manualStore(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'siswa_id' => ['required', 'integer', 'exists:siswa,id'],
            'jenis' => ['required', 'in:masuk,pulang'],
            'jam' => ['nullable', 'date_format:H:i'],
            'status' => ['nullable', 'in:hadir,izin,sakit,alpha'],
            'keterangan' => ['nullable', 'string', 'max:255'],
            'override_cutoff' => ['nullable', 'boolean'],
            'kelas_id' => ['nullable'],
        ]);

        $tanggal = $validated['tanggal'];
        $siswaId = (int) $validated['siswa_id'];
        $jenis = $validated['jenis'];
        $jam = ($validated['jam'] ?? now()->format('H:i')) . ':00';
        $status = $validated['status'] ?? 'hadir';
        $keterangan = trim((string) ($validated['keterangan'] ?? ''));
        if ($keterangan === '') {
            $keterangan = null;
        }
        $overrideCutoff = (bool) ($validated['override_cutoff'] ?? false);
        $cutoffMasuk = $this->getMasukCutoff();
        $jamInput = Carbon::createFromFormat('H:i:s', $jam);
        $jamCutoff = Carbon::createFromFormat('H:i', $cutoffMasuk);
        $isTerlambat = $jamInput->gt($jamCutoff);

        $absensi = Absensi::where('siswa_id', $siswaId)
            ->whereDate('tanggal', $tanggal)
            ->first();

        if ($jenis === 'masuk') {
            if (!$overrideCutoff && $jamInput->gt($jamCutoff)) {
                return redirect('/absensi/manual?' . http_build_query([
                    'tanggal' => $tanggal,
                    'kelas_id' => $validated['kelas_id'] ?? null,
                ]))->with('error', "Jam masuk melewati batas {$cutoffMasuk}. Centang override jika memang perlu.");
            }

            if ($absensi) {
                return redirect('/absensi/manual?' . http_build_query([
                    'tanggal' => $tanggal,
                    'kelas_id' => $validated['kelas_id'] ?? null,
                ]))->with('error', 'Absensi masuk untuk siswa ini pada tanggal tersebut sudah ada.');
            }

            Absensi::create([
                'siswa_id' => $siswaId,
                'tanggal' => $tanggal,
                'jam_masuk' => $jam,
                'status' => $status,
                'keterangan' => $keterangan,
                'sumber' => 'manual',
                'terlambat' => $isTerlambat,
            ]);

            return redirect('/absensi/manual?' . http_build_query([
                'tanggal' => $tanggal,
                'kelas_id' => $validated['kelas_id'] ?? null,
            ]))->with('success', 'Absensi masuk manual berhasil disimpan.');
        }

        if (!$absensi) {
            return redirect('/absensi/manual?' . http_build_query([
                'tanggal' => $tanggal,
                'kelas_id' => $validated['kelas_id'] ?? null,
            ]))->with('error', 'Belum ada absensi masuk untuk siswa ini pada tanggal tersebut.');
        }

        if ($absensi->jam_pulang !== null) {
            return redirect('/absensi/manual?' . http_build_query([
                'tanggal' => $tanggal,
                'kelas_id' => $validated['kelas_id'] ?? null,
            ]))->with('error', 'Absensi pulang untuk siswa ini sudah tercatat.');
        }

        $absensi->update([
            'jam_pulang' => $jam,
            'keterangan' => $keterangan ?? $absensi->keterangan,
        ]);

        return redirect('/absensi/manual?' . http_build_query([
            'tanggal' => $tanggal,
            'kelas_id' => $validated['kelas_id'] ?? null,
        ]))->with('success', 'Absensi pulang manual berhasil disimpan.');
    }

    public function monitor()
    {
        $today = now()->toDateString();
        $monitorData = $this->getMonitorData($today, 25);

        return view('absensi.monitor', [
            'today' => $today,
            'summary' => $monitorData['summary'],
            'aktivitasTerbaru' => $monitorData['aktivitas'],
        ]);
    }

    public function monitorData(Request $request)
    {
        $tanggal = $request->input('tanggal', now()->toDateString());
        $limit = (int) $request->input('limit', 25);
        if ($limit <= 0) {
            $limit = 25;
        }
        if ($limit > 100) {
            $limit = 100;
        }

        $monitorData = $this->getMonitorData($tanggal, $limit);

        return response()->json([
            'status' => 'ok',
            'tanggal' => $tanggal,
            'summary' => $monitorData['summary'],
            'aktivitas' => $monitorData['aktivitas']->map(function ($item) {
                return [
                    'updated_at' => (string) $item->updated_at,
                    'nis' => $item->siswa->nis ?? '-',
                    'nama' => $item->siswa->nama ?? '-',
                    'kelas' => $item->siswa->kelas->nama_kelas ?? '-',
                    'jam_masuk' => $item->jam_masuk ?? '-',
                    'jam_pulang' => $item->jam_pulang ?? '-',
                    'status_absensi' => $item->status,
                    'keterangan' => $item->keterangan ?? '-',
                    'sumber' => $item->sumber ?? '-',
                    'terlambat' => (bool) $item->terlambat,
                ];
            })->values(),
            'server_time' => now()->toDateTimeString(),
        ]);
    }

    public function rekapSederhana(Request $request)
    {
        $tanggal = $request->input('tanggal', now()->toDateString());
        $kelasId = $request->input('kelas_id');

        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $hadirList = Absensi::with('siswa.kelas')
            ->whereDate('tanggal', $tanggal)
            ->where('status', 'hadir')
            ->when($kelasId, function ($query, $kelasId) {
                $query->whereHas('siswa', function ($siswaQuery) use ($kelasId) {
                    $siswaQuery->where('kelas_id', $kelasId);
                });
            })
            ->orderBy('jam_masuk')
            ->get();

        return view('absensi.rekap-sederhana', [
            'tanggal' => $tanggal,
            'kelasId' => $kelasId,
            'kelasList' => $kelasList,
            'hadirList' => $hadirList,
        ]);
    }

    public function riwayat(Request $request)
    {
        $tanggal = $request->input('tanggal', now()->toDateString());
        $kelasId = $request->input('kelas_id');
        $q = trim((string) $request->input('q', ''));
        $statusFilter = $request->input('status_filter');
        $sumberFilter = $request->input('sumber_filter');
        $terlambatFilter = $request->input('terlambat_filter');
        $perPage = (int) $request->input('per_page', 20);
        if ($perPage <= 0) {
            $perPage = 20;
        }

        $kelasList = Kelas::orderBy('nama_kelas')->get();

        $query = $this->buildRiwayatQuery(
            $tanggal,
            $kelasId,
            $q,
            $statusFilter,
            $sumberFilter,
            $terlambatFilter
        );

        $riwayat = (clone $query)
            ->orderBy('jam_masuk')
            ->paginate($perPage)
            ->withQueryString();

        $summary = [
            'total' => (clone $query)->count(),
            'sudah_pulang' => (clone $query)->whereNotNull('jam_pulang')->count(),
            'belum_pulang' => (clone $query)->whereNull('jam_pulang')->count(),
        ];

        $rekapKelas = Kelas::query()
            ->select(['id', 'nama_kelas'])
            ->when($kelasId, function ($query, $kelasId) {
                $query->where('id', $kelasId);
            })
            ->withCount([
                'siswa as total_siswa',
                'siswa as total_masuk' => function ($query) use ($tanggal) {
                    $query->whereHas('absensi', function ($absensiQuery) use ($tanggal) {
                        $absensiQuery->whereDate('tanggal', $tanggal);
                    });
                },
                'siswa as total_pulang' => function ($query) use ($tanggal) {
                    $query->whereHas('absensi', function ($absensiQuery) use ($tanggal) {
                        $absensiQuery->whereDate('tanggal', $tanggal)
                            ->whereNotNull('jam_pulang');
                    });
                },
            ])
            ->orderBy('nama_kelas')
            ->get();

        $siswaBaseQuery = Siswa::with('kelas')
            ->when($kelasId, function ($query, $kelasId) {
                $query->where('kelas_id', $kelasId);
            });

        $siswaSudahMasukIds = Absensi::whereDate('tanggal', $tanggal)
            ->when($kelasId, function ($query, $kelasId) {
                $query->whereHas('siswa', function ($siswaQuery) use ($kelasId) {
                    $siswaQuery->where('kelas_id', $kelasId);
                });
            })
            ->pluck('siswa_id');

        $siswaBelumMasuk = (clone $siswaBaseQuery)
            ->whereNotIn('id', $siswaSudahMasukIds)
            ->orderBy('nama')
            ->get();

        $siswaBelumPulang = Siswa::with('kelas')
            ->whereIn('id', function ($subQuery) use ($tanggal, $kelasId) {
                $subQuery->select('siswa_id')
                    ->from('absensi')
                    ->whereDate('tanggal', $tanggal)
                    ->whereNull('jam_pulang');
            })
            ->when($kelasId, function ($query, $kelasId) {
                $query->where('kelas_id', $kelasId);
            })
            ->orderBy('nama')
            ->get();

        return view('absensi.riwayat', [
            'riwayat' => $riwayat,
            'kelasList' => $kelasList,
            'tanggal' => $tanggal,
            'kelasId' => $kelasId,
            'q' => $q,
            'statusFilter' => $statusFilter,
            'sumberFilter' => $sumberFilter,
            'terlambatFilter' => $terlambatFilter,
            'perPage' => $perPage,
            'summary' => $summary,
            'rekapKelas' => $rekapKelas,
            'siswaBelumMasuk' => $siswaBelumMasuk,
            'siswaBelumPulang' => $siswaBelumPulang,
        ]);
    }

    public function exportRiwayat(Request $request)
    {
        $tanggal = $request->input('tanggal', now()->toDateString());
        $kelasId = $request->input('kelas_id');
        $q = trim((string) $request->input('q', ''));
        $statusFilter = $request->input('status_filter');
        $sumberFilter = $request->input('sumber_filter');
        $terlambatFilter = $request->input('terlambat_filter');

        $riwayat = $this->buildRiwayatQuery(
            $tanggal,
            $kelasId,
            $q,
            $statusFilter,
            $sumberFilter,
            $terlambatFilter
        )
            ->orderBy('jam_masuk')
            ->get();

        $filename = 'riwayat_absensi_' . $tanggal;
        if (!empty($kelasId)) {
            $filename .= '_kelas_' . $kelasId;
        }
        $filename .= '.csv';

        return response()->streamDownload(function () use ($riwayat) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'NIS',
                'Nama',
                'Kelas',
                'Tanggal',
                'Jam Masuk',
                'Jam Pulang',
                'Status',
                'Keterangan',
                'Sumber',
                'Terlambat',
                'Foto Masuk',
                'Foto Pulang',
            ]);

            foreach ($riwayat as $item) {
                fputcsv($handle, [
                    $item->siswa->nis ?? '',
                    $item->siswa->nama ?? '',
                    $item->siswa->kelas->nama_kelas ?? '',
                    $item->tanggal,
                    $item->jam_masuk ?? '',
                    $item->jam_pulang ?? '',
                    $item->status,
                    $item->keterangan ?? '',
                    $item->sumber ?? '',
                    $item->terlambat ? 'ya' : 'tidak',
                    $item->foto_masuk ?? '',
                    $item->foto_pulang ?? '',
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function updateStatus(Request $request, Absensi $absensi)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:hadir,izin,sakit,alpha'],
            'tanggal' => ['nullable', 'date'],
            'kelas_id' => ['nullable'],
            'q' => ['nullable', 'string'],
            'status_filter' => ['nullable', 'in:hadir,izin,sakit,alpha'],
            'sumber_filter' => ['nullable', 'in:scan_qr,manual,auto_alpha'],
            'terlambat_filter' => ['nullable', 'in:ya,tidak'],
            'per_page' => ['nullable'],
            'page' => ['nullable'],
        ]);

        $absensi->update([
            'status' => $validated['status'],
        ]);

        return redirect('/absensi/riwayat?' . http_build_query([
            'tanggal' => $validated['tanggal'] ?? now()->toDateString(),
            'kelas_id' => $validated['kelas_id'] ?? null,
            'q' => $validated['q'] ?? null,
            'status_filter' => $validated['status_filter'] ?? null,
            'sumber_filter' => $validated['sumber_filter'] ?? null,
            'terlambat_filter' => $validated['terlambat_filter'] ?? null,
            'per_page' => $validated['per_page'] ?? null,
            'page' => $validated['page'] ?? null,
        ]))->with('success', 'Status absensi berhasil diperbarui.');
    }

    public function markAlpha(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'kelas_id' => ['nullable'],
            'q' => ['nullable', 'string'],
            'status_filter' => ['nullable', 'in:hadir,izin,sakit,alpha'],
            'sumber_filter' => ['nullable', 'in:scan_qr,manual,auto_alpha'],
            'terlambat_filter' => ['nullable', 'in:ya,tidak'],
            'per_page' => ['nullable'],
        ]);

        $tanggal = $validated['tanggal'];
        $kelasId = $validated['kelas_id'] ?? null;

        $siswaQuery = Siswa::query()
            ->when($kelasId, function ($query, $kelasId) {
                $query->where('kelas_id', $kelasId);
            });

        $sudahAdaAbsensiIds = Absensi::whereDate('tanggal', $tanggal)
            ->when($kelasId, function ($query, $kelasId) {
                $query->whereHas('siswa', function ($siswaQuery) use ($kelasId) {
                    $siswaQuery->where('kelas_id', $kelasId);
                });
            })
            ->pluck('siswa_id');

        $siswaBelumAbsenIds = (clone $siswaQuery)
            ->whereNotIn('id', $sudahAdaAbsensiIds)
            ->pluck('id');

        $totalDitandai = 0;

        foreach ($siswaBelumAbsenIds as $siswaId) {
            Absensi::create([
                'siswa_id' => $siswaId,
                'tanggal' => $tanggal,
                'status' => 'alpha',
                'sumber' => 'auto_alpha',
                'terlambat' => false,
            ]);
            $totalDitandai++;
        }

        return redirect('/absensi/riwayat?' . http_build_query([
            'tanggal' => $validated['tanggal'],
            'kelas_id' => $validated['kelas_id'] ?? null,
            'q' => $validated['q'] ?? null,
            'status_filter' => $validated['status_filter'] ?? null,
            'sumber_filter' => $validated['sumber_filter'] ?? null,
            'terlambat_filter' => $validated['terlambat_filter'] ?? null,
            'per_page' => $validated['per_page'] ?? null,
        ]))->with('success', "Berhasil menandai {$totalDitandai} siswa sebagai alpha.");
    }

    public function unmarkAlpha(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'kelas_id' => ['nullable'],
            'q' => ['nullable', 'string'],
            'status_filter' => ['nullable', 'in:hadir,izin,sakit,alpha'],
            'sumber_filter' => ['nullable', 'in:scan_qr,manual,auto_alpha'],
            'terlambat_filter' => ['nullable', 'in:ya,tidak'],
            'per_page' => ['nullable'],
        ]);

        $tanggal = $validated['tanggal'];
        $kelasId = $validated['kelas_id'] ?? null;

        $query = Absensi::whereDate('tanggal', $tanggal)
            ->where('status', 'alpha')
            ->whereNull('jam_masuk')
            ->whereNull('jam_pulang')
            ->whereNull('foto_masuk')
            ->whereNull('foto_pulang')
            ->when($kelasId, function ($query, $kelasId) {
                $query->whereHas('siswa', function ($siswaQuery) use ($kelasId) {
                    $siswaQuery->where('kelas_id', $kelasId);
                });
            });

        $totalDibatalkan = $query->count();
        $query->delete();

        return redirect('/absensi/riwayat?' . http_build_query([
            'tanggal' => $validated['tanggal'],
            'kelas_id' => $validated['kelas_id'] ?? null,
            'q' => $validated['q'] ?? null,
            'status_filter' => $validated['status_filter'] ?? null,
            'sumber_filter' => $validated['sumber_filter'] ?? null,
            'terlambat_filter' => $validated['terlambat_filter'] ?? null,
            'per_page' => $validated['per_page'] ?? null,
        ]))->with('success', "Berhasil membatalkan {$totalDibatalkan} data alpha otomatis.");
    }

    public function destroy(Request $request, Absensi $absensi)
    {
        $validated = $request->validate([
            'tanggal' => ['nullable', 'date'],
            'kelas_id' => ['nullable'],
            'q' => ['nullable', 'string'],
            'status_filter' => ['nullable', 'in:hadir,izin,sakit,alpha'],
            'sumber_filter' => ['nullable', 'in:scan_qr,manual,auto_alpha'],
            'terlambat_filter' => ['nullable', 'in:ya,tidak'],
            'per_page' => ['nullable'],
            'page' => ['nullable'],
        ]);

        $fotoMasuk = $absensi->foto_masuk;
        $fotoPulang = $absensi->foto_pulang;
        $absensi->delete();
        $this->deleteDatasetFile($fotoMasuk);
        $this->deleteDatasetFile($fotoPulang);

        return redirect('/absensi/riwayat?' . http_build_query([
            'tanggal' => $validated['tanggal'] ?? now()->toDateString(),
            'kelas_id' => $validated['kelas_id'] ?? null,
            'q' => $validated['q'] ?? null,
            'status_filter' => $validated['status_filter'] ?? null,
            'sumber_filter' => $validated['sumber_filter'] ?? null,
            'terlambat_filter' => $validated['terlambat_filter'] ?? null,
            'per_page' => $validated['per_page'] ?? null,
            'page' => $validated['page'] ?? null,
        ]))->with('success', 'Data absensi berhasil dihapus.');
    }

    public function updateWaktu(Request $request, Absensi $absensi)
    {
        $validated = $request->validate([
            'jam_masuk' => ['nullable', 'date_format:H:i'],
            'jam_pulang' => ['nullable', 'date_format:H:i'],
            'tanggal' => ['nullable', 'date'],
            'kelas_id' => ['nullable'],
            'q' => ['nullable', 'string'],
            'status_filter' => ['nullable', 'in:hadir,izin,sakit,alpha'],
            'sumber_filter' => ['nullable', 'in:scan_qr,manual,auto_alpha'],
            'terlambat_filter' => ['nullable', 'in:ya,tidak'],
            'per_page' => ['nullable'],
            'page' => ['nullable'],
        ]);

        $jamMasuk = $validated['jam_masuk'] ?? null;
        $jamPulang = $validated['jam_pulang'] ?? null;

        if ($jamPulang !== null && $jamMasuk === null) {
            return redirect('/absensi/riwayat?' . http_build_query([
                'tanggal' => $validated['tanggal'] ?? now()->toDateString(),
                'kelas_id' => $validated['kelas_id'] ?? null,
                'q' => $validated['q'] ?? null,
                'status_filter' => $validated['status_filter'] ?? null,
                'sumber_filter' => $validated['sumber_filter'] ?? null,
                'terlambat_filter' => $validated['terlambat_filter'] ?? null,
                'per_page' => $validated['per_page'] ?? null,
                'page' => $validated['page'] ?? null,
            ]))->with('error', 'Jam pulang tidak bisa diisi jika jam masuk kosong.');
        }

        if ($jamMasuk !== null && $jamPulang !== null && $jamPulang < $jamMasuk) {
            return redirect('/absensi/riwayat?' . http_build_query([
                'tanggal' => $validated['tanggal'] ?? now()->toDateString(),
                'kelas_id' => $validated['kelas_id'] ?? null,
                'q' => $validated['q'] ?? null,
                'status_filter' => $validated['status_filter'] ?? null,
                'sumber_filter' => $validated['sumber_filter'] ?? null,
                'terlambat_filter' => $validated['terlambat_filter'] ?? null,
                'per_page' => $validated['per_page'] ?? null,
                'page' => $validated['page'] ?? null,
            ]))->with('error', 'Jam pulang tidak boleh lebih kecil dari jam masuk.');
        }

        $absensi->update([
            'jam_masuk' => $jamMasuk ? $jamMasuk . ':00' : null,
            'jam_pulang' => $jamPulang ? $jamPulang . ':00' : null,
        ]);

        return redirect('/absensi/riwayat?' . http_build_query([
            'tanggal' => $validated['tanggal'] ?? now()->toDateString(),
            'kelas_id' => $validated['kelas_id'] ?? null,
            'q' => $validated['q'] ?? null,
            'status_filter' => $validated['status_filter'] ?? null,
            'sumber_filter' => $validated['sumber_filter'] ?? null,
            'terlambat_filter' => $validated['terlambat_filter'] ?? null,
            'per_page' => $validated['per_page'] ?? null,
            'page' => $validated['page'] ?? null,
        ]))->with('success', 'Jam masuk/pulang berhasil diperbarui.');
    }

    private function buildRiwayatQuery(
        string $tanggal,
        ?string $kelasId,
        string $q = '',
        ?string $statusFilter = null,
        ?string $sumberFilter = null,
        ?string $terlambatFilter = null
    )
    {
        return Absensi::with(['siswa.kelas'])
            ->whereDate('tanggal', $tanggal)
            ->when($kelasId, function ($query, $kelasId) {
                $query->whereHas('siswa', function ($siswaQuery) use ($kelasId) {
                    $siswaQuery->where('kelas_id', $kelasId);
                });
            })
            ->when($statusFilter, function ($query, $statusFilter) {
                $query->where('status', $statusFilter);
            })
            ->when($sumberFilter, function ($query, $sumberFilter) {
                $query->where('sumber', $sumberFilter);
            })
            ->when($terlambatFilter === 'ya', function ($query) {
                $query->where('terlambat', true);
            })
            ->when($terlambatFilter === 'tidak', function ($query) {
                $query->where('terlambat', false);
            })
            ->when($q !== '', function ($query) use ($q) {
                $query->whereHas('siswa', function ($siswaQuery) use ($q) {
                    $siswaQuery->where(function ($inner) use ($q) {
                        $inner->where('nama', 'like', '%' . $q . '%')
                            ->orWhere('nis', 'like', '%' . $q . '%');
                    });
                });
            });
    }

    private function getMonitorData(string $tanggal, int $limit): array
    {
        $summary = [
            'masuk' => Absensi::whereDate('tanggal', $tanggal)->count(),
            'pulang' => Absensi::whereDate('tanggal', $tanggal)->whereNotNull('jam_pulang')->count(),
            'alpha' => Absensi::whereDate('tanggal', $tanggal)->where('status', 'alpha')->count(),
            'belum_pulang' => Absensi::whereDate('tanggal', $tanggal)->whereNull('jam_pulang')->count(),
        ];

        $aktivitas = Absensi::with(['siswa.kelas'])
            ->whereDate('tanggal', $tanggal)
            ->orderByDesc('updated_at')
            ->limit($limit)
            ->get();

        return [
            'summary' => $summary,
            'aktivitas' => $aktivitas,
        ];
    }

    private function getMasukCutoff(): string
    {
        $cutoff = (string) env('ABSENSI_CUTOFF_MASUK', '09:00');
        if (!preg_match('/^\d{2}:\d{2}$/', $cutoff)) {
            return '09:00';
        }

        return $cutoff;
    }

    public function scan(Request $request)
    {
        $request->validate([
            'qr_token' => ['required', 'string'],
        ]);

        $siswa = Siswa::where('qr_token', $request->qr_token)->first();

        if (!$siswa) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Siswa tidak ditemukan'
            ]);
        }

        $today = now()->toDateString();
        $absensiHariIni = Absensi::where('siswa_id', $siswa->id)
            ->whereDate('tanggal', $today)
            ->first();

        $canMasuk = $absensiHariIni === null;
        $canPulang = $absensiHariIni !== null && $absensiHariIni->jam_pulang === null;
        $alreadyComplete = $absensiHariIni !== null && $absensiHariIni->jam_pulang !== null;

        return response()->json([
            'status' => 'ok',
            'siswa_id' => $siswa->id,
            'nama' => $siswa->nama,
            'can_masuk' => $canMasuk,
            'can_pulang' => $canPulang,
            'already_complete' => $alreadyComplete,
            'jam_masuk' => $absensiHariIni?->jam_masuk,
            'jam_pulang' => $absensiHariIni?->jam_pulang,
        ]);
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'siswa_id' => ['required', 'integer', 'exists:siswa,id'],
            'foto' => ['required', 'string'],
            'jenis' => ['required', 'in:masuk,pulang'],
        ]);

        $siswa = Siswa::find($request->siswa_id);

        if (!$siswa) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Siswa tidak ditemukan'
            ]);
        }

        $today = now()->toDateString();
        $absensiHariIni = Absensi::where('siswa_id', $siswa->id)
            ->whereDate('tanggal', $today)
            ->first();

        $jenis = $request->jenis;

        if ($jenis === 'masuk' && $absensiHariIni) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Absensi masuk hari ini sudah tercatat'
            ], 409);
        }

        if ($jenis === 'pulang' && !$absensiHariIni) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Belum ada absensi masuk hari ini'
            ], 409);
        }

        if ($jenis === 'pulang' && $absensiHariIni->jam_pulang !== null) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Absensi pulang hari ini sudah tercatat'
            ], 409);
        }

        $fotoBase64 = $request->foto;
        $mime = null;
        $base64Body = $fotoBase64;

        if (preg_match('/^data:image\/(jpeg|jpg|png);base64,(.+)$/', $fotoBase64, $matches)) {
            $mime = strtolower($matches[1]);
            $base64Body = $matches[2];
        }

        $base64Body = str_replace(' ', '+', $base64Body);
        $imageBinary = base64_decode($base64Body, true);

        if ($imageBinary === false) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Format foto tidak valid'
            ], 422);
        }

        if (strlen($imageBinary) > 5 * 1024 * 1024) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Ukuran foto terlalu besar (maksimal 5MB)'
            ], 422);
        }

        $imageInfo = @getimagesizefromstring($imageBinary);
        if ($imageInfo === false || !in_array($imageInfo['mime'] ?? '', ['image/jpeg', 'image/png'], true)) {
            return response()->json([
                'status' => 'error',
                'msg' => 'File foto harus berupa JPEG atau PNG yang valid'
            ], 422);
        }

        $extension = (($imageInfo['mime'] ?? '') === 'image/png') ? 'png' : 'jpg';
        $filename = 'siswa_' . $siswa->id . '_' . $jenis . '_' . now()->format('YmdHis') . '_' . Str::lower(Str::random(6)) . '.' . $extension;
        $relativePath = 'dataset/' . $filename;

        Storage::disk('public')->makeDirectory('dataset');
        Storage::disk('public')->put($relativePath, $imageBinary);

        if ($jenis === 'masuk') {
            try {
                $cutoffMasuk = $this->getMasukCutoff();
                $jamNow = Carbon::createFromFormat('H:i:s', now()->toTimeString());
                $jamCutoff = Carbon::createFromFormat('H:i', $cutoffMasuk);
                $isTerlambat = $jamNow->gt($jamCutoff);

                Absensi::create([
                    'siswa_id'   => $siswa->id,
                    'tanggal'    => $today,
                    'jam_masuk'  => now()->toTimeString(),
                    'foto_masuk' => $filename,
                    'status'     => 'hadir',
                    'sumber'     => 'scan_qr',
                    'terlambat'  => $isTerlambat,
                ]);
            } catch (QueryException $e) {
                if ((string) $e->getCode() === '23000') {
                    Storage::disk('public')->delete($relativePath);
                    return response()->json([
                        'status' => 'error',
                        'msg' => 'Absensi masuk hari ini sudah tercatat'
                    ], 409);
                }

                Storage::disk('public')->delete($relativePath);
                throw $e;
            }
        } else {
            try {
                $oldFotoPulang = $absensiHariIni->foto_pulang;
                $absensiHariIni->update([
                    'jam_pulang' => now()->toTimeString(),
                    'foto_pulang' => $filename,
                ]);
                if ($oldFotoPulang && $oldFotoPulang !== $filename) {
                    $this->deleteDatasetFile($oldFotoPulang);
                }
            } catch (\Throwable $e) {
                Storage::disk('public')->delete($relativePath);
                throw $e;
            }
        }

        return response()->json([
            'status' => 'ok',
            'nama'   => $siswa->nama,
            'jenis'  => $jenis,
        ]);
    }

    private function deleteDatasetFile(?string $filename): void
    {
        if (!$filename) {
            return;
        }

        Storage::disk('public')->delete('dataset/' . $filename);
    }
}
