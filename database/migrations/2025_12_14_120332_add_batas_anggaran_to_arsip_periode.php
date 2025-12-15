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
       Schema::table('arsip_periode', function (Blueprint $table) {
    $table->decimal('batas_anggaran', 15, 2)->after('total_anggaran');
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
