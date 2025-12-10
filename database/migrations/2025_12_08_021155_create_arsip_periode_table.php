<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
   Schema::create('arsip_periode', function (Blueprint $table) {
    $table->id();
    $table->string('nama_periode');
    $table->integer('total_spt');
    $table->integer('total_luar_riau');
    $table->integer('total_dalam_riau');
    $table->integer('total_siak');
    $table->decimal('total_anggaran', 15, 2);
    $table->timestamps();
});



}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arsip_periode');
    }
};
