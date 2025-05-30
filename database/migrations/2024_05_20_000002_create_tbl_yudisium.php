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
        Schema::create('tbl_yudisium', function (Blueprint $table) {
            $table->id('id_yudisium');
            $table->unsignedBigInteger('id_kota');
            $table->integer('kategori_yudisium');
            $table->date('tanggal_yudisium');
            $table->decimal('nilai_akhir', 3, 2)->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected']);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_kota')->references('id_kota')->on('tbl_kota')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_yudisium');
    }
};