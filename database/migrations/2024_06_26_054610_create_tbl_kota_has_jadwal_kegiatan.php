<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tbl_kota_has_jadwal_kegiatan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_kota');
            $table->foreign('id_kota')->references('id_kota')->on('tbl_kota')->onDelete('cascade');
            $table->unsignedBigInteger('id_jadwal_kegiatan');
            $table->foreign('id_jadwal_kegiatan')->references('id')->on('tbl_jadwal_kegiatan')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kegiatan_mingguans');
    }
};