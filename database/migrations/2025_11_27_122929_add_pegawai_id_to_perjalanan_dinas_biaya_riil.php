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
        Schema::table('perjalanan_dinas_biaya_riil', function (Blueprint $table) {
            $table->unsignedBigInteger('pegawai_id')->nullable()->after('perjalanan_dinas_id');
        });
    }

    public function down()
    {
        Schema::table('perjalanan_dinas_biaya_riil', function (Blueprint $table) {
            $table->dropColumn('pegawai_id');
        });
    }
};
