<?php

namespace App\Http\Controllers;

use App\Models\OrangTuaSiswa;
use App\Models\Siswa;
use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrangTuaSiswaController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->get();
        $siswa = Siswa::with('kelas')->orderBy('nama')->get();
        $relasi = OrangTuaSiswa::with(['user', 'siswa.kelas'])
            ->latest()
            ->get();

        return view('orangtua.relasi', [
            'users' => $users,
            'siswa' => $siswa,
            'relasi' => $relasi,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'siswa_id' => ['required', 'integer', 'exists:siswa,id'],
            'hubungan' => ['nullable', 'string', 'max:50'],
        ]);

        $exists = OrangTuaSiswa::where('user_id', $validated['user_id'])
            ->where('siswa_id', $validated['siswa_id'])
            ->exists();

        if ($exists) {
            return redirect('/orang-tua/relasi')->with('error', 'Relasi orang tua dan siswa sudah ada.');
        }

        $relasi = OrangTuaSiswa::create($validated);

        ActivityLogger::log(
            'orang_tua.relasi_store',
            'Relasi orang tua dan siswa ditambahkan.',
            $relasi,
            $validated
        );

        return redirect('/orang-tua/relasi')->with('success', 'Relasi orang tua-siswa berhasil ditambahkan.');
    }

    public function destroy(OrangTuaSiswa $relasi)
    {
        $properties = [
            'user_id' => $relasi->user_id,
            'siswa_id' => $relasi->siswa_id,
        ];

        $relasi->delete();

        ActivityLogger::log(
            'orang_tua.relasi_destroy',
            'Relasi orang tua dan siswa dihapus.',
            null,
            $properties
        );

        return redirect('/orang-tua/relasi')->with('success', 'Relasi orang tua-siswa berhasil dihapus.');
    }

    public function generateCode(User $user)
    {
        if ($user->role !== 'orang_tua') {
            return redirect('/orang-tua/relasi')->with('error', 'Kode hanya bisa dibuat untuk user role orang_tua.');
        }

        $code = $this->generateUniqueCode();
        $expiresAt = now()->addHours(24);

        $user->update([
            'parent_access_code' => $code,
            'parent_access_code_expires_at' => $expiresAt,
        ]);

        ActivityLogger::log(
            'orang_tua.generate_code',
            "Kode akses orang tua dibuat untuk {$user->name}.",
            $user,
            [
                'expires_at' => $expiresAt->toDateTimeString(),
            ]
        );

        return redirect('/orang-tua/relasi')->with(
            'success',
            "Kode akses untuk {$user->name}: {$code} (berlaku sampai {$expiresAt->format('Y-m-d H:i')})"
        );
    }

    private function generateUniqueCode(): string
    {
        do {
            $code = strtoupper(Str::random(6));
        } while (User::where('parent_access_code', $code)->exists());

        return $code;
    }
}
