<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\JadwalPelajaran;
use Illuminate\Http\Request;

class JadwalPelajaranController extends Controller
{
    public function index()
    {
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $mataPelajaranList = MataPelajaran::orderBy('nama')->get();
        $jadwalList = JadwalPelajaran::with(['kelas', 'mataPelajaran'])
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get();

        return view('jadwal.index', [
            'kelasList' => $kelasList,
            'mataPelajaranList' => $mataPelajaranList,
            'jadwalList' => $jadwalList,
        ]);
    }

    public function storeMataPelajaran(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
        ]);

        MataPelajaran::create([
            'nama' => $validated['nama'],
        ]);

        return redirect('/jadwal')->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function updateMataPelajaran(Request $request, MataPelajaran $mapel)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
        ]);

        $mapel->update([
            'nama' => $validated['nama'],
        ]);

        return redirect('/jadwal')->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroyMataPelajaran(MataPelajaran $mapel)
    {
        if ($mapel->jadwalPelajaran()->count() > 0) {
            return redirect('/jadwal')->with('error', 'Mata pelajaran tidak bisa dihapus karena masih dipakai di jadwal.');
        }

        $mapel->delete();

        return redirect('/jadwal')->with('success', 'Mata pelajaran berhasil dihapus.');
    }

    public function storeJadwal(Request $request)
    {
        $validated = $request->validate([
            'kelas_id' => ['required', 'integer', 'exists:kelas,id'],
            'mata_pelajaran_id' => ['required', 'integer', 'exists:mata_pelajaran,id'],
            'hari' => ['required', 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i'],
        ]);

        if ($validated['jam_selesai'] <= $validated['jam_mulai']) {
            return redirect('/jadwal')->with('error', 'Jam selesai harus lebih besar dari jam mulai.');
        }

        $jamMulai = $validated['jam_mulai'] . ':00';
        $jamSelesai = $validated['jam_selesai'] . ':00';

        if ($this->hasScheduleConflict(
            (int) $validated['kelas_id'],
            $validated['hari'],
            $jamMulai,
            $jamSelesai
        )) {
            return redirect('/jadwal')->with('error', 'Bentrok jadwal: kelas dan hari yang sama memiliki rentang jam overlap.');
        }

        JadwalPelajaran::create([
            'kelas_id' => $validated['kelas_id'],
            'mata_pelajaran_id' => $validated['mata_pelajaran_id'],
            'hari' => $validated['hari'],
            'jam_mulai' => $jamMulai,
            'jam_selesai' => $jamSelesai,
        ]);

        return redirect('/jadwal')->with('success', 'Jadwal pelajaran berhasil ditambahkan.');
    }

    public function updateJadwal(Request $request, JadwalPelajaran $jadwal)
    {
        $validated = $request->validate([
            'kelas_id' => ['required', 'integer', 'exists:kelas,id'],
            'mata_pelajaran_id' => ['required', 'integer', 'exists:mata_pelajaran,id'],
            'hari' => ['required', 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i'],
        ]);

        if ($validated['jam_selesai'] <= $validated['jam_mulai']) {
            return redirect('/jadwal')->with('error', 'Jam selesai harus lebih besar dari jam mulai.');
        }

        $jamMulai = $validated['jam_mulai'] . ':00';
        $jamSelesai = $validated['jam_selesai'] . ':00';

        if ($this->hasScheduleConflict(
            (int) $validated['kelas_id'],
            $validated['hari'],
            $jamMulai,
            $jamSelesai,
            $jadwal->id
        )) {
            return redirect('/jadwal')->with('error', 'Bentrok jadwal: kelas dan hari yang sama memiliki rentang jam overlap.');
        }

        $jadwal->update([
            'kelas_id' => $validated['kelas_id'],
            'mata_pelajaran_id' => $validated['mata_pelajaran_id'],
            'hari' => $validated['hari'],
            'jam_mulai' => $jamMulai,
            'jam_selesai' => $jamSelesai,
        ]);

        return redirect('/jadwal')->with('success', 'Jadwal pelajaran berhasil diperbarui.');
    }

    public function destroy(JadwalPelajaran $jadwal)
    {
        $jadwal->delete();

        return redirect('/jadwal')->with('success', 'Jadwal pelajaran berhasil dihapus.');
    }

    private function hasScheduleConflict(
        int $kelasId,
        string $hari,
        string $jamMulai,
        string $jamSelesai,
        ?int $ignoreId = null
    ): bool {
        return JadwalPelajaran::query()
            ->where('kelas_id', $kelasId)
            ->where('hari', $hari)
            ->when($ignoreId, function ($query, $ignoreId) {
                $query->where('id', '!=', $ignoreId);
            })
            ->where('jam_mulai', '<', $jamSelesai)
            ->where('jam_selesai', '>', $jamMulai)
            ->exists();
    }
}
