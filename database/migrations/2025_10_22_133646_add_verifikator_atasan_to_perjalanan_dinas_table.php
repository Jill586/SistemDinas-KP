<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan kolom verifikator_id dan atasan_id.
     */
    public function up(): void
    {
        Schema::table('perjalanan_dinas', function (Blueprint $table) {
            // Tambah kolom baru setelah kolom tertentu (opsional)
            $table->unsignedBigInteger('verifikator_id')->nullable()->after('operator_id');
            $table->unsignedBigInteger('atasan_id')->nullable()->after('verifikator_id');

            // Jika relasi ke tabel users
            $table->foreign('verifikator_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('atasan_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Rollback kolom jika dibatalkan.
     */
    public function down(): void
    {
        Schema::table('perjalanan_dinas', function (Blueprint $table) {
            $table->dropForeign(['verifikator_id']);
            $table->dropForeign(['atasan_id']);
            $table->dropColumn(['verifikator_id', 'atasan_id']);
        });
    }
};
