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
        Schema::create('permintaan_no_surat', function (Blueprint $table) {
            $table->id();
            $table->string('tx_number');
            $table->integer('created_by');
            $table->string('catatan')->nullable();
            $table->integer('penandatangan');

            $table->foreign('tx_number')->references('tx_number')->on('surat_keluar');
            $table->foreign('created_by')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permintaan_no_surats');
    }
};
