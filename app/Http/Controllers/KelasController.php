<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $kelasList = Kelas::withCount('siswa')
            ->orderBy('nama_kelas')
            ->get();

        return view('kelas.index', [
            'kelasList' => $kelasList,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kelas' => ['required', 'string', 'max:100'],
            'tahun_ajaran' => ['required', 'string', 'max:20'],
        ]);

        Kelas::create($validated);

        return redirect('/kelas')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function update(Request $request, Kelas $kelas)
    {
        $validated = $request->validate([
            'nama_kelas' => ['required', 'string', 'max:100'],
            'tahun_ajaran' => ['required', 'string', 'max:20'],
        ]);

        $kelas->update($validated);

        return redirect('/kelas')->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kelas)
    {
        if ($kelas->siswa()->count() > 0) {
            return redirect('/kelas')->with('error', 'Kelas tidak bisa dihapus karena masih memiliki data siswa.');
        }

        $kelas->delete();

        return redirect('/kelas')->with('success', 'Kelas berhasil dihapus.');
    }
}
