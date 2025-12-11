<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom bidang ke tabel pegawai.
     */
    public function up(): void
    {
        Schema::table('pegawai', function (Blueprint $table) {
            $table->string('bidang')->nullable()->after('pangkat_golongan');
        });
    }

    /**
     * Hapus kolom bidang saat rollback.
     */
    public function down(): void
    {
        Schema::table('pegawai', function (Blueprint $table) {
            $table->dropColumn('bidang');
        });
    }
};
