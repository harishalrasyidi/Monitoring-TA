<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyAbstractColumnNullableOnTblKotaHasArtefakTable extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_kota_has_artefak', function (Blueprint $table) {
            $table->text('abstract')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('tbl_kota_has_artefak', function (Blueprint $table) {
            $table->text('abstract')->nullable(false)->change();
        });
    }
}
