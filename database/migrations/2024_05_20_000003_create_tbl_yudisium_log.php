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
        Schema::create('tbl_yudisium_log', function (Blueprint $table) {
            $table->id('id_log');
            $table->unsignedBigInteger('id_yudisium');
            $table->unsignedBigInteger('id_user');
            $table->string('jenis_perubahan');
            $table->text('nilai_lama')->nullable();
            $table->text('nilai_baru')->nullable();
            $table->timestamp('waktu_perubahan')->useCurrent();
            $table->text('keterangan')->nullable();

            $table->foreign('id_yudisium')->references('id_yudisium')->on('tbl_yudisium')->onDelete('cascade');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_yudisium_log');
    }
};