<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFilterColumnsToKota extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_kota', function (Blueprint $table) {
            $table->string('prodi')->nullable()->after('periode');
            $table->string('kbk')->nullable()->after('prodi');
            $table->string('topik')->nullable()->after('kbk');
            $table->year('tahun')->nullable()->after('topik');
            $table->enum('jenis_ta', ['analisis', 'development'])->nullable()->after('tahun');
            $table->string('metodologi')->nullable()->after('jenis_ta');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_kota', function (Blueprint $table) {
            $table->dropColumn(['prodi', 'kbk', 'topik', 'tahun', 'jenis_ta', 'metodologi']);
        });
    }
}
