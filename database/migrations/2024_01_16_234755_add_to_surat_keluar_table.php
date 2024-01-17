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
        Schema::table('surat_keluar', function (Blueprint $table) {
            $table->integer('tujuan_surat');
            $table->integer('asal_surat');
            $table->integer('entity_tujuan_surat');
            $table->string('entity_tujuan_surat_detail');

            $table->foreign('tujuan_surat')->references('id')->on('m_tujuan_surat');
            $table->foreign('entity_tujuan_surat')->references('id')->on('m_entity_tujuan_surat');
            $table->foreign('asal_surat')->references('id')->on('organization');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_keluar', function (Blueprint $table) {
            //
        });
    }
};
