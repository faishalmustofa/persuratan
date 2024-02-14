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
        Schema::table('surat_masuk', function (Blueprint $table) {
            $table->integer('entity_asal_surat');
            $table->string('entity_asal_surat_detail');
            $table->string('lampiran_type')->nullable();
            $table->integer('jml_lampiran')->nullable();
            $table->text('catatan')->nullable();
            $table->integer('klasifikasi');
            $table->integer('derajat');
            $table->string('file_path')->nullable();

            $table->foreign('entity_asal_surat')->references('id')->on('m_entity_asal_surat');
            $table->foreign('klasifikasi')->references('id')->on('r_klasifikasi_surat');
            $table->foreign('derajat')->references('id')->on('r_derajat_surat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_masuk', function (Blueprint $table) {
            //
        });
    }
};
