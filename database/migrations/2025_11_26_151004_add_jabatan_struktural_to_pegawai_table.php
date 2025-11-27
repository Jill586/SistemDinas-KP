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
        Schema::table('pegawai', function (Blueprint $table) {
            // Menambahkan kolom jabatan_struktural
            $table->string('jabatan_struktural', 150)
                  ->nullable()
                  ->after('jabatan_id'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pegawai', function (Blueprint $table) {
            // Menghapus kolom jabatan_struktural
            $table->dropColumn('jabatan_struktural');
        });
    }
};
