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
        Schema::create('tbl_timeline_has_artefak', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_timeline');
            $table->foreign('id_timeline')->references('id_timeline')->on('tbl_timeline')->onDelete('cascade');
            $table->unsignedBigInteger('id_master_artefak');
            $table->foreign('id_master_artefak')->references('id')->on('tbl_master_artefak')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_timeline_has_artefak');
    }
};
