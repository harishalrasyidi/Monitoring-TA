<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kota_has_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kota_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('role_in_group', ['ketua', 'anggota', 'pembimbing'])->default('anggota');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');ign('kota_id')->references('id')->on('tbl_kota')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('tbl_users')->onDelete('cascade');
            
            // Ensure unique combination
            $table->unique(['kota_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kota_has_user');
    }
};