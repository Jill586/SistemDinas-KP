<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('perjalanan_dinas', function (Blueprint $table) {
            // Tambahkan kolom operator_id setelah kolom id
            $table->unsignedBigInteger('operator_id')->nullable()->after('id');

            // Buat foreign key relasi ke tabel users
            $table->foreign('operator_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('perjalanan_dinas', function (Blueprint $table) {
            // Hapus foreign key dan kolom saat rollback
            $table->dropForeign(['operator_id']);
            $table->dropColumn('operator_id');
        });
    }
};
