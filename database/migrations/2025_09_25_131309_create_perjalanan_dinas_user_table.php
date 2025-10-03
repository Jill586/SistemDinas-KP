<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perjalanan_dinas_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('perjalanan_dinas_id');
            $table->unsignedBigInteger('pegawai_id');
            $table->timestamps();

            $table->foreign('perjalanan_dinas_id')
                ->references('id')->on('perjalanan_dinas')
                ->onDelete('cascade');

            $table->foreign('pegawai_id')
                ->references('id')->on('pegawai')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perjalanan_dinas_user');
    }
};
