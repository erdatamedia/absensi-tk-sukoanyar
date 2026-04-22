<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\Kelas;
use App\Support\Branding;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class AppSettingController extends Controller
{
    public function edit()
    {
        return view('settings.school', [
            'settings' => [
                'school_name' => Branding::schoolName(),
                'school_tagline' => Branding::schoolTagline(),
                'school_logo_url' => Branding::logoUrl(),
                'operational_start' => Branding::operationalStart(),
                'operational_end' => Branding::operationalEnd(),
            ],
            'previewCard' => [
                'nama' => 'Budi Santoso',
                'nis' => '001',
                'kelas' => 'TK A',
                'tanggal_lahir' => '01/01/2020',
                'jenis_kelamin' => 'Laki-laki',
                'alamat' => 'Sukoanyar',
            ],
            'kelasList' => Kelas::orderBy('nama_kelas')->get(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'school_name' => ['required', 'string', 'max:120'],
            'school_tagline' => ['nullable', 'string', 'max:120'],
            'operational_start' => ['required', 'date_format:H:i'],
            'operational_end' => ['required', 'date_format:H:i'],
            'school_logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_logo' => ['nullable', 'boolean'],
        ]);

        if (! Schema::hasTable('app_settings')) {
            return back()->withErrors([
                'school_name' => 'Tabel pengaturan sekolah belum tersedia di server. Jalankan migration terlebih dahulu.',
            ])->withInput();
        }

        try {
            $currentLogo = Branding::logoPath();
            $logoPath = $currentLogo;

            if ($request->boolean('remove_logo') && $currentLogo) {
                Storage::disk('public')->delete($currentLogo);
                $logoPath = null;
            }

            if ($request->hasFile('school_logo')) {
                if ($currentLogo) {
                    Storage::disk('public')->delete($currentLogo);
                }

                $logoPath = $request->file('school_logo')->store('branding', 'public');
            }

            AppSetting::updateOrCreate(['key' => 'school_name'], ['value' => $validated['school_name']]);
            AppSetting::updateOrCreate(['key' => 'school_tagline'], ['value' => $validated['school_tagline'] ?? '']);
            AppSetting::updateOrCreate(['key' => 'operational_start'], ['value' => $validated['operational_start']]);
            AppSetting::updateOrCreate(['key' => 'operational_end'], ['value' => $validated['operational_end']]);
            AppSetting::updateOrCreate(['key' => 'school_logo_path'], ['value' => $logoPath]);

            Branding::forgetCache();

            return redirect()->route('settings.school.edit')->with('success', 'Pengaturan sekolah berhasil diperbarui.');
        } catch (\Throwable $e) {
            Log::error('Gagal menyimpan pengaturan sekolah.', [
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors([
                'school_logo' => 'Pengaturan sekolah gagal disimpan di server. Periksa permission storage, symlink public/storage, dan migration tabel app_settings.',
            ])->withInput();
        }
    }
}
