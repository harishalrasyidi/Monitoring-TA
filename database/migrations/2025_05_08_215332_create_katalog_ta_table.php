<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKatalogTATable extends Migration
{
    public function up()
    {
        Schema::create('tbl_katalog_ta', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_kota');
            $table->string('judul_ta');
            $table->text('deskripsi')->nullable();
            $table->string('file_ta');
            $table->timestamp('waktu_pengumpulan')->useCurrent();
            $table->timestamps();

            $table->foreign('id_kota')->references('id_kota')->on('tbl_kota')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_katalog_ta');
    }
}
