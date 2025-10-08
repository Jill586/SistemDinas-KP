<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel biaya riil
        Schema::create('perjalanan_dinas_biaya_riil', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perjalanan_dinas_id')
                ->constrained('perjalanan_dinas')
                ->onDelete('cascade');

            $table->string('deskripsi_biaya');
            $table->integer('jumlah')->default(1);
            $table->string('satuan')->nullable();
            $table->decimal('harga_satuan', 15, 2)->default(0);
            $table->string('nomor_bukti')->nullable();
            $table->text('keterangan_tambahan')->nullable();
            $table->decimal('subtotal_biaya', 15, 2)->default(0);

            $table->timestamps();
        });

        // Tambahkan kolom status_laporan di tabel perjalanan_dinas (jika belum ada)
        Schema::table('perjalanan_dinas', function (Blueprint $table) {
            if (!Schema::hasColumn('perjalanan_dinas', 'status_laporan')) {
                $table->enum('status_laporan', ['belum_dibuat', 'proses', 'selesai'])
                    ->default('belum_dibuat')
                    ->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perjalanan_dinas_biaya_riil');

        Schema::table('perjalanan_dinas', function (Blueprint $table) {
            if (Schema::hasColumn('perjalanan_dinas', 'status_laporan')) {
                $table->dropColumn('status_laporan');
            }
        });
    }
};
