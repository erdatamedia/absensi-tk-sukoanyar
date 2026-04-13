<?php

namespace Tests\Feature;

use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AbsensiFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_scan_save_attendance_and_see_it_in_rekap(): void
    {
        Storage::fake('public');
        Carbon::setTestNow('2026-04-13 07:30:00');

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $kelas = Kelas::create([
            'nama_kelas' => 'TK A',
            'tahun_ajaran' => '2025/2026',
        ]);

        $siswa = Siswa::create([
            'nis' => 'S-001',
            'nama' => 'Budi',
            'kelas_id' => $kelas->id,
            'jenis_kelamin' => 'L',
        ]);

        $this->actingAs($admin)
            ->postJson('/absensi/scan', [
                'qr_token' => $siswa->qr_token,
            ])
            ->assertOk()
            ->assertJson([
                'status' => 'ok',
                'siswa_id' => $siswa->id,
                'nama' => 'Budi',
                'can_masuk' => true,
                'can_pulang' => false,
            ]);

        $this->actingAs($admin)
            ->postJson('/absensi/simpan', [
                'siswa_id' => $siswa->id,
                'foto' => $this->sampleBase64Png(),
                'jenis' => 'masuk',
            ])
            ->assertOk()
            ->assertJson([
                'status' => 'ok',
                'nama' => 'Budi',
                'jenis' => 'masuk',
            ]);

        $absensiMasuk = Absensi::firstOrFail();

        $this->assertNotNull($absensiMasuk->foto_masuk);
        $this->assertSame('hadir', $absensiMasuk->status);
        $this->assertSame('scan_qr', $absensiMasuk->sumber);
        Storage::disk('public')->assertExists('dataset/' . $absensiMasuk->foto_masuk);

        $this->actingAs($admin)
            ->postJson('/absensi/scan', [
                'qr_token' => $siswa->qr_token,
            ])
            ->assertOk()
            ->assertJson([
                'status' => 'ok',
                'siswa_id' => $siswa->id,
                'can_masuk' => false,
                'can_pulang' => true,
            ]);

        $this->actingAs($admin)
            ->get('/absensi/rekap?tanggal=2026-04-13')
            ->assertOk()
            ->assertSee('Budi')
            ->assertSee($absensiMasuk->foto_masuk);

        Carbon::setTestNow('2026-04-13 12:45:00');

        $this->actingAs($admin)
            ->postJson('/absensi/simpan', [
                'siswa_id' => $siswa->id,
                'foto' => $this->sampleBase64Png(),
                'jenis' => 'pulang',
            ])
            ->assertOk()
            ->assertJson([
                'status' => 'ok',
                'nama' => 'Budi',
                'jenis' => 'pulang',
            ]);

        $absensiMasuk->refresh();

        $this->assertNotNull($absensiMasuk->jam_pulang);
        $this->assertNotNull($absensiMasuk->foto_pulang);
        Storage::disk('public')->assertExists('dataset/' . $absensiMasuk->foto_pulang);
    }

    public function test_deleting_absensi_also_deletes_saved_dataset_files(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $kelas = Kelas::create([
            'nama_kelas' => 'TK B',
            'tahun_ajaran' => '2025/2026',
        ]);

        $siswa = Siswa::create([
            'nis' => 'S-002',
            'nama' => 'Siti',
            'kelas_id' => $kelas->id,
            'jenis_kelamin' => 'P',
        ]);

        Storage::disk('public')->put('dataset/masuk-test.jpg', 'masuk');
        Storage::disk('public')->put('dataset/pulang-test.jpg', 'pulang');

        $absensi = Absensi::create([
            'siswa_id' => $siswa->id,
            'tanggal' => '2026-04-13',
            'jam_masuk' => '07:00:00',
            'jam_pulang' => '11:00:00',
            'foto_masuk' => 'masuk-test.jpg',
            'foto_pulang' => 'pulang-test.jpg',
            'status' => 'hadir',
            'sumber' => 'scan_qr',
            'terlambat' => false,
        ]);

        $this->actingAs($admin)
            ->delete('/absensi/' . $absensi->id, [
                'tanggal' => '2026-04-13',
            ])
            ->assertRedirect('/absensi/riwayat?tanggal=2026-04-13');

        $this->assertDatabaseMissing('absensi', [
            'id' => $absensi->id,
        ]);

        Storage::disk('public')->assertMissing('dataset/masuk-test.jpg');
        Storage::disk('public')->assertMissing('dataset/pulang-test.jpg');
    }

    public function test_scan_returns_error_for_unknown_qr_token(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($admin)
            ->postJson('/absensi/scan', [
                'qr_token' => 'qr-token-tidak-ada',
            ])
            ->assertOk()
            ->assertJson([
                'status' => 'error',
                'msg' => 'Siswa tidak ditemukan',
            ]);
    }

    public function test_save_returns_validation_error_for_invalid_photo_payload(): void
    {
        Storage::fake('public');
        Carbon::setTestNow('2026-04-13 07:30:00');

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $kelas = Kelas::create([
            'nama_kelas' => 'TK C',
            'tahun_ajaran' => '2025/2026',
        ]);

        $siswa = Siswa::create([
            'nis' => 'S-003',
            'nama' => 'Dina',
            'kelas_id' => $kelas->id,
            'jenis_kelamin' => 'P',
        ]);

        $this->actingAs($admin)
            ->postJson('/absensi/simpan', [
                'siswa_id' => $siswa->id,
                'foto' => 'bukan-base64-image',
                'jenis' => 'masuk',
            ])
            ->assertStatus(422)
            ->assertJson([
                'status' => 'error',
                'msg' => 'Format foto tidak valid',
            ]);

        $this->assertDatabaseMissing('absensi', [
            'siswa_id' => $siswa->id,
            'tanggal' => '2026-04-13',
        ]);
    }

    public function test_save_returns_conflict_when_pulang_is_sent_before_masuk(): void
    {
        Storage::fake('public');
        Carbon::setTestNow('2026-04-13 12:30:00');

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $kelas = Kelas::create([
            'nama_kelas' => 'TK D',
            'tahun_ajaran' => '2025/2026',
        ]);

        $siswa = Siswa::create([
            'nis' => 'S-004',
            'nama' => 'Rafi',
            'kelas_id' => $kelas->id,
            'jenis_kelamin' => 'L',
        ]);

        $this->actingAs($admin)
            ->postJson('/absensi/simpan', [
                'siswa_id' => $siswa->id,
                'foto' => $this->sampleBase64Png(),
                'jenis' => 'pulang',
            ])
            ->assertStatus(409)
            ->assertJson([
                'status' => 'error',
                'msg' => 'Belum ada absensi masuk hari ini',
            ]);

        $this->assertDatabaseMissing('absensi', [
            'siswa_id' => $siswa->id,
            'tanggal' => '2026-04-13',
        ]);
    }

    public function test_save_returns_conflict_when_masuk_is_sent_twice_in_same_day(): void
    {
        Storage::fake('public');
        Carbon::setTestNow('2026-04-13 07:15:00');

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $kelas = Kelas::create([
            'nama_kelas' => 'TK E',
            'tahun_ajaran' => '2025/2026',
        ]);

        $siswa = Siswa::create([
            'nis' => 'S-005',
            'nama' => 'Naya',
            'kelas_id' => $kelas->id,
            'jenis_kelamin' => 'P',
        ]);

        $this->actingAs($admin)
            ->postJson('/absensi/simpan', [
                'siswa_id' => $siswa->id,
                'foto' => $this->sampleBase64Png(),
                'jenis' => 'masuk',
            ])
            ->assertOk();

        $this->actingAs($admin)
            ->postJson('/absensi/simpan', [
                'siswa_id' => $siswa->id,
                'foto' => $this->sampleBase64Png(),
                'jenis' => 'masuk',
            ])
            ->assertStatus(409)
            ->assertJson([
                'status' => 'error',
                'msg' => 'Absensi masuk hari ini sudah tercatat',
            ]);

        $this->assertDatabaseCount('absensi', 1);
    }

    public function test_save_returns_conflict_when_pulang_is_sent_twice_in_same_day(): void
    {
        Storage::fake('public');
        Carbon::setTestNow('2026-04-13 07:20:00');

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $kelas = Kelas::create([
            'nama_kelas' => 'TK F',
            'tahun_ajaran' => '2025/2026',
        ]);

        $siswa = Siswa::create([
            'nis' => 'S-006',
            'nama' => 'Fikri',
            'kelas_id' => $kelas->id,
            'jenis_kelamin' => 'L',
        ]);

        $this->actingAs($admin)
            ->postJson('/absensi/simpan', [
                'siswa_id' => $siswa->id,
                'foto' => $this->sampleBase64Png(),
                'jenis' => 'masuk',
            ])
            ->assertOk();

        Carbon::setTestNow('2026-04-13 12:00:00');

        $this->actingAs($admin)
            ->postJson('/absensi/simpan', [
                'siswa_id' => $siswa->id,
                'foto' => $this->sampleBase64Png(),
                'jenis' => 'pulang',
            ])
            ->assertOk();

        $this->actingAs($admin)
            ->postJson('/absensi/simpan', [
                'siswa_id' => $siswa->id,
                'foto' => $this->sampleBase64Png(),
                'jenis' => 'pulang',
            ])
            ->assertStatus(409)
            ->assertJson([
                'status' => 'error',
                'msg' => 'Absensi pulang hari ini sudah tercatat',
            ]);

        $this->assertDatabaseCount('absensi', 1);
    }

    private function sampleBase64Png(): string
    {
        return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAusB9pP6KyYAAAAASUVORK5CYII=';
    }
}
