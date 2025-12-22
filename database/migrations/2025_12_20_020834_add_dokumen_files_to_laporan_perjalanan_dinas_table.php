<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('laporan_perjalanan_dinas', function (Blueprint $table) {

            // STATUS BAYAR (ENUM)
            $table->enum('status_bayar', ['belum_bayar', 'verifikasi','sudah_bayar'])
                  ->default('belum_bayar')
                  ->after('total_biaya_rill_dilaporkan');

            // Dokumen utama
            $table->string('file_spt')->nullable()->after('status_bayar');
            $table->string('file_sppd')->nullable()->after('file_spt');

            // Dokumen pendukung
            $table->string('file_surat_undangan')->nullable()->after('file_sppd');
            $table->string('file_laporan_perjadin')->nullable()->after('file_surat_undangan');
            $table->string('file_bukti_pengeluaran')->nullable()->after('file_laporan_perjadin');
            $table->string('file_spm')->nullable()->after('file_bukti_pengeluaran');
            $table->string('file_surat_30_persen_penginapan')->nullable()->after('file_spm');
            $table->string('file_kwitansi')->nullable()->after('file_surat_30_persen_penginapan');
        });
    }

    public function down(): void
    {
        Schema::table('laporan_perjalanan_dinas', function (Blueprint $table) {
            $table->dropColumn([
                'status_bayar',
                'file_spt',
                'file_sppd',
                'file_surat_undangan',
                'file_laporan_perjadin',
                'file_bukti_pengeluaran',
                'file_spm',
                'file_surat_30_persen_penginapan',
                'file_kwitansi',
            ]);
        });
    }
};
