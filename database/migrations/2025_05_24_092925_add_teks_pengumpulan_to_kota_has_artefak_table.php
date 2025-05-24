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
        Schema::table('tbl_kota_has_artefak', function (Blueprint $table) {
            // Menambahkan kolom teks_pengumpulan
            $table->text('teks_pengumpulan')->nullable(); // Menambahkan kolom teks_pengumpulan yang bisa null
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_kota_has_artefak', function (Blueprint $table) {
            // Menghapus kolom teks_pengumpulan saat rollback
            $table->dropColumn('teks_pengumpulan');
        });
    }
};
