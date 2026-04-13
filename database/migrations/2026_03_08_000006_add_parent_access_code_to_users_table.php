<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('parent_access_code', 12)->nullable()->unique()->after('role');
            $table->timestamp('parent_access_code_expires_at')->nullable()->after('parent_access_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['parent_access_code']);
            $table->dropColumn(['parent_access_code', 'parent_access_code_expires_at']);
        });
    }
};
