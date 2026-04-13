<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentCodeAuthController extends Controller
{
    public function showForm()
    {
        return view('orangtua.login-kode');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'kode' => ['required', 'string', 'max:12'],
        ]);

        $kode = strtoupper(trim($validated['kode']));

        $user = User::where('role', 'orang_tua')
            ->where('parent_access_code', $kode)
            ->whereNotNull('parent_access_code_expires_at')
            ->where('parent_access_code_expires_at', '>=', now())
            ->first();

        if (!$user) {
            return back()->with('error', 'Kode tidak valid atau sudah kedaluwarsa.');
        }

        Auth::login($user);

        return redirect('/orang-tua/absensi-anak');
    }
}
