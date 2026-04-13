<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            $version = (string) DB::scalar('select version()');

            // Workaround for MySQL 9.x local builds that can crash on ADD UNIQUE for this table.
            if (preg_match('/^9\./', $version) === 1) {
                return;
            }
        }

        Schema::table('absensi', function (Blueprint $table) {
            $table->unique(['siswa_id', 'tanggal'], 'absensi_siswa_tanggal_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            Schema::table('absensi', function (Blueprint $table) {
                $table->dropUnique('absensi_siswa_tanggal_unique');
            });
        } catch (\Throwable $e) {
            // Index may not exist when skipped by MySQL 9.x workaround.
        }
    }
};
