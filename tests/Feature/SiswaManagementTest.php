<?php

namespace Tests\Feature;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

class SiswaManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_import_siswa_from_excel_template(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Kelas::create([
            'nama_kelas' => 'TK A',
            'tahun_ajaran' => '2025/2026',
        ]);

        $file = $this->makeImportFile();

        $response = $this->actingAs($admin)->post(route('siswa.import'), [
            'file' => $file,
        ]);

        $response->assertRedirect(route('siswa.index'));
        $this->assertDatabaseHas('siswa', [
            'nis' => '2026001',
            'nama' => 'Alya Putri',
            'jenis_kelamin' => 'P',
            'alamat' => 'Sukoanyar',
            'keterangan' => 'Aktif',
        ]);
        $this->assertDatabaseHas('kelas', [
            'nama_kelas' => 'TK B',
        ]);
    }

    public function test_admin_can_render_student_card_pdf(): void
    {
        [$admin, $siswa] = $this->makeStudent();

        $response = $this->actingAs($admin)->get(route('siswa.card-pdf', $siswa));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_admin_can_render_mass_student_cards_pdf_for_eight_per_page(): void
    {
        [$admin] = $this->makeStudent();
        $kelas = Kelas::first();

        Siswa::create([
            'nis' => '2026002',
            'nama' => 'Bagas Pratama',
            'kelas_id' => $kelas->id,
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2020-01-01',
            'alamat' => 'Wajak',
            'keterangan' => 'Aktif',
        ]);

        $response = $this->actingAs($admin)->get(route('siswa.cards-pdf', ['per_page' => 8, 'show_address' => 0]));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_admin_can_render_mass_student_cards_pdf_for_one_per_page(): void
    {
        [$admin] = $this->makeStudent();

        $response = $this->actingAs($admin)->get(route('siswa.cards-pdf', ['per_page' => 1, 'show_address' => 1]));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }


    public function test_admin_can_filter_mass_student_cards_by_kelas(): void
    {
        [$admin] = $this->makeStudent();
        $kelasB = Kelas::create([
            'nama_kelas' => 'TK B',
            'tahun_ajaran' => '2025/2026',
        ]);

        Siswa::create([
            'nis' => '2026003',
            'nama' => 'Citra Ayu',
            'kelas_id' => $kelasB->id,
            'jenis_kelamin' => 'P',
            'tanggal_lahir' => '2020-02-02',
            'alamat' => 'Wajak',
            'keterangan' => 'Aktif',
        ]);

        $response = $this->actingAs($admin)->get(route('siswa.cards-pdf', [
            'per_page' => 8,
            'kelas_id' => $kelasB->id,
            'show_address' => 0,
        ]));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_admin_can_update_school_settings_and_upload_logo(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->put(route('settings.school.update'), [
            'school_name' => 'TK PGRI 2 Wajak',
            'school_tagline' => 'Absensi Harian Sekolah',
            'operational_start' => '06:30',
            'operational_end' => '13:00',
            'school_logo' => UploadedFile::fake()->image('logo.png', 256, 256),
        ]);

        $response->assertRedirect(route('settings.school.edit'));
        $this->assertDatabaseHas('app_settings', ['key' => 'school_name', 'value' => 'TK PGRI 2 Wajak']);
        $this->assertDatabaseHas('app_settings', ['key' => 'school_tagline', 'value' => 'Absensi Harian Sekolah']);
        $this->assertDatabaseHas('app_settings', ['key' => 'operational_start', 'value' => '06:30']);
        $this->assertDatabaseHas('app_settings', ['key' => 'operational_end', 'value' => '13:00']);
        $logoPath = \App\Models\AppSetting::where('key', 'school_logo_path')->value('value');
        Storage::disk('public')->assertExists($logoPath);
    }

    protected function makeStudent(): array
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $kelas = Kelas::create([
            'nama_kelas' => 'TK A',
            'tahun_ajaran' => '2025/2026',
        ]);
        $siswa = Siswa::create([
            'nis' => '2026001',
            'nama' => 'Alya Putri',
            'kelas_id' => $kelas->id,
            'jenis_kelamin' => 'P',
            'tanggal_lahir' => '2019-05-18',
            'alamat' => 'Sukoanyar',
            'keterangan' => 'Aktif',
        ]);

        return [$admin, $siswa];
    }

    protected function makeImportFile(): UploadedFile
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray([
            ['DATA SISWA'],
            [''],
            ['NO.', 'NIS', 'NAMA SISWA', 'TANGGAL LAHIR (dd/mm/yyyy)', 'JENIS KELAMIN (L/P)', 'KELAS', 'KETERANGAN', 'ALAMAT'],
            [1, '2026001', 'Alya Putri', '18/05/2019', 'P', 'A', 'Aktif', 'Sukoanyar'],
            [2, '2026002', 'Bagas Pratama', '2020-01-01', 'L', 'B', null, 'Wajak'],
        ]);

        $path = storage_path('app/testing-siswa-import.xlsx');
        (new Xlsx($spreadsheet))->save($path);

        return new UploadedFile($path, 'testing-siswa-import.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);
    }
}
