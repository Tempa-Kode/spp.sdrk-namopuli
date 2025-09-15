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
        Schema::table('transaksi', function (Blueprint $table) {
            $table->unsignedBigInteger('transaksi_gabungan_id')->nullable()->after('snap_token');
            $table->foreign('transaksi_gabungan_id')->references('id')->on('transaksi')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropForeign(['transaksi_gabungan_id']);
            $table->dropColumn('transaksi_gabungan_id');
        });
    }
};
