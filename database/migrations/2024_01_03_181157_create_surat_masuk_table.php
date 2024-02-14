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
        Schema::create('surat_masuk', function (Blueprint $table) {
            $table->string('tx_number')->primary();
            $table->string('no_agenda')->unique()->nullable();
            $table->string('no_surat');
            $table->date('tgl_surat');
            $table->integer('asal_surat');
            $table->integer('tujuan_surat');
            $table->string('perihal');
            $table->date('tgl_diterima');
            $table->string('lampiran')->nullable();
            $table->string('tembusan')->nullable();
            $table->integer('status_surat');
            $table->integer('created_by');

            $table->foreign('asal_surat')->references('id')->on('m_asal_surat');
            $table->foreign('status_surat')->references('id')->on('m_status_surat');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('tujuan_surat')->references('id')->on('organization');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_masuk');
    }
};
