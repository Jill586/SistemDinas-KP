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
        Schema::create('sbu_items', function (Blueprint $table) {
            $table->id();
            $table->string('kategori_biaya', 255);
            $table->string('uraian_biaya', 255);
            $table->string('provinsi_tujuan', 255)->nullable();
            $table->string('kota_tujuan', 255)->nullable();
            $table->string('kecamatan_tujuan', 255)->nullable();
            $table->string('desa_tujuan', 255)->nullable();
            $table->string('satuan', 255);
            $table->decimal('besaran_biaya', 15, 2);
            $table->string('tipe_perjalanan', 255);
            $table->string('tingkat_pejabat_atau_golongan', 255)->nullable();
            $table->text('keterangan')->nullable();
            $table->integer('jarak_km_min')->nullable();
            $table->integer('jarak_km_max')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sbu_items');
    }
};
