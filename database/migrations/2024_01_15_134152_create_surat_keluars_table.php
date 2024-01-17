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
        Schema::create('surat_keluar', function (Blueprint $table) {
            $table->string('tx_number')->primary();
            $table->integer('jenis_surat');
            $table->string('no_agenda')->unique()->nullable();
            $table->date('tgl_surat');
            $table->string('no_surat');
            $table->string('perihal');
            $table->integer('tujuan_surat');
            $table->string('lampiran')->nullable();
            $table->string('lampiran_type')->nullable();
            $table->string('jml_lampiran')->nullable();
            $table->string('konseptor')->nullable();
            $table->integer('unit_kerja')->nullable();
            $table->string('penandatangan_surat')->nullable();
            $table->text('catatan')->nullable();
            $table->integer('status_surat')->nullable();
            $table->integer('created_by');
            $table->string('file_path')->nullable();

            $table->foreign('jenis_surat')->references('id')->on('r_jenis_surat');
            $table->foreign('status_surat')->references('id')->on('m_status_surat');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('tujuan_surat')->references('id')->on('organization');
            $table->foreign('unit_kerja')->references('id')->on('organization');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_keluars');
    }
};
