<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perjalanan_dinas', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_spt')->nullable(); // nomor_spt
            $table->date('tanggal_spt'); // tanggal_spt
            $table->enum('jenis_spt', ['dalam_daerah', 'luar_daerah_dalam_provinsi', 'luar_daerah_luar_provinsi']); // jenis_spt
            $table->string('jenis_kegiatan')->nullable(); // jenis_kegiatan
            $table->string('tujuan_spt')->nullable(); // tujuan_spt
            $table->string('provinsi_tujuan_id')->nullable(); // provinsi_tujuan_id
            $table->string('kota_tujuan_id')->nullable(); // kota_tujuan_id
            $table->decimal('jarak_km', 8, 2)->nullable(); // jarak_km
            $table->string('kecamatan_spt')->nullable(); // kecamatan_spt
            $table->string('desa_spt')->nullable(); // desa_spt
            $table->text('dasar_spt'); // dasar_spt
            $table->text('uraian_spt'); // uraian_spt
            $table->string('bukti_undangan')->nullable(); // bukti_undangan
            $table->string('alat_angkut')->nullable(); // alat_angkut
            $table->integer('lama_hari')->nullable(); // lama_hari
            $table->date('tanggal_mulai'); // tanggal_mulai
            $table->date('tanggal_selesai'); // tanggal_selesai
            $table->enum('status', ['draft','diproses','revisi_operator','verifikasi','ditolak','disetujui'])->default('draft'); // status
            $table->text('catatan_verifikator')->nullable(); // catatan_verifikator
            $table->text('catatan_atasan')->nullable(); // catatan_atasan
            $table->timestamps();
        });

        // Tabel pivot untuk banyak pegawai
        Schema::create('perjalanan_dinas_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perjalanan_dinas_id')->constrained()->onDelete('cascade');
            $table->foreignId('pegawai_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perjalanan_dinas_user');
        Schema::dropIfExists('perjalanan_dinas');
    }
};
