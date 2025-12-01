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
        Schema::table('laporan_perjalanan_dinas', function (Blueprint $table) {
            $table->json('foto_dokumentasi')->nullable()->after('saran_tindak_lanjut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_perjalanan_dinas', function (Blueprint $table) {
            $table->dropColumn('foto_dokumentasi');
        });
    }
};
