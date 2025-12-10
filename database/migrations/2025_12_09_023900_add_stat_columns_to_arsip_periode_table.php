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
    Schema::table('arsip_periode', function (Blueprint $table) {
        $table->integer('total_pengeluaran')->default(0);
        $table->integer('total_realcost')->default(0);
        $table->integer('perjalanan_hari_ini')->default(0);
        $table->integer('perjalanan_bulan_ini')->default(0);
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('arsip_periode', function (Blueprint $table) {
            //
        });
    }
};
