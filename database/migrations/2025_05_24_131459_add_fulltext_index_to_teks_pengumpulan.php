<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddFulltextIndexToTeksPengumpulan extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE tbl_kota_has_artefak ADD FULLTEXT fulltext_teks_pengumpulan (teks_pengumpulan)');
    }

    public function down()
    {
        DB::statement('ALTER TABLE tbl_kota_has_artefak DROP INDEX fulltext_teks_pengumpulan');
    }
}
