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
        Schema::create('tbl_kegiatan_has_timeline', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_timeline');
            $table->foreign('id_timeline')->references('id_timeline')->on('tbl_timeline')->onDelete('cascade');
            $table->unsignedBigInteger('id_jadwal_kegiatan');
            $table->foreign('id_jadwal_kegiatan')->references('id')->on('tbl_jadwal_kegiatan')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_kegiatan_has_tahapan_progres');
    }
};
