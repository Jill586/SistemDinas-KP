<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_perjalanan_dinas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perjalanan_dinas_id')->constrained('perjalanan_dinas')->onDelete('cascade');
            $table->foreignId('pegawai_id')->constrained('pegawai');
            $table->date('tanggal_laporan');
            $table->text('ringkasan_hasil_kegiatan');
            $table->text('kendala_dihadapi')->nullable();
            $table->text('saran_tindak_lanjut')->nullable();
            $table->text('catatan_preview')->nullable();
            $table->decimal('total_biaya_rill_dilaporkan', 15, 2)->nullable();
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_perjalanan_dinas');
    }
};
