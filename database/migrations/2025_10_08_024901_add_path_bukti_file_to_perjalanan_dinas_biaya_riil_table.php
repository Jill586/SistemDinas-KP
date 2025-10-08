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
        if (!Schema::hasColumn('perjalanan_dinas_biaya_riil', 'path_bukti_file')) {
            $table->string('path_bukti_file')->nullable()->after('nomor_bukti');
        }
    });
}

public function down(): void
{
    Schema::table('perjalanan_dinas_biaya_riil', function (Blueprint $table) {
        if (Schema::hasColumn('perjalanan_dinas_biaya_riil', 'path_bukti_file')) {
            $table->dropColumn('path_bukti_file');
        }
    });
}

};
