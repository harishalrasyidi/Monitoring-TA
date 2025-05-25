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
        Schema::create('tbl_kota', function (Blueprint $table) {
            $table->id('id_kota');
            $table->string('nama_kota');
            $table->string('judul');
            $table->text('abstrak');
            $table->string('kelas');
            $table->integer('periode');
            $table->tinyInteger('kategori');
            $table->string('metodologi', 50);
            $table->smallInteger('prodi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_kota');
    }
};