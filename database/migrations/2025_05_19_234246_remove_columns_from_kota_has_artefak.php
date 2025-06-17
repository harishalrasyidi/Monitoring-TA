<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveColumnsFromKotaHasArtefak extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_kota_has_artefak', function (Blueprint $table) {
            $table->dropColumn(['kategori', 'prodi']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_kota_has_artefak', function (Blueprint $table) {
            $table->string('kategori')->nullable();
            $table->string('prodi')->nullable();
        });
    }
}