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
        Schema::create('penerimaan_pindah_berkas_surat_masuk', function (Blueprint $table) {
            $table->id();
            $table->string('tx_number');
            $table->string('pangkat_penerima');
            $table->string('nama_penerima');
            $table->string('jabatan_penerima');
            $table->dateTime('tgl_diterima');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penerimaan_pindah_berkas_surat_masuk');
    }
};
