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
        Schema::table('perjalanan_dinas_biaya_riil', function (Blueprint $table) {
            $table->string('provinsi_tujuan')->nullable()->after('deskripsi_biaya');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perjalanan_dinas_biaya_riil', function (Blueprint $table) {
            $table->dropColumn('provinsi_tujuan');
        });
    }
};
