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
        Schema::table('pengguna', function (Blueprint $table) {
            $table->foreignId('siswa_id')->after('id')->nullable()->constrained('siswa')->onDelete('cascade');
            $table->foreignId('petugas_id')->after('siswa_id')->nullable()->constrained('guru_pegawai')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengguna', function (Blueprint $table) {
            $table->dropForeign(['siswa_id']);
            $table->dropForeign(['petugas_id']);
            $table->dropColumn(['siswa_id', 'petugas_id']);
        });
    }
};
