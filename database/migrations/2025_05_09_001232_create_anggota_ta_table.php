<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnggotaTATable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_anggota_ta', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_katalog_ta');
            $table->unsignedBigInteger('id_user');
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('id_katalog_ta')->references('id')->on('tbl_katalog_ta')->onDelete('cascade');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            
            // Unique constraint untuk mencegah duplikat
            $table->unique(['id_katalog_ta', 'id_user']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_anggota_ta');
    }
}
