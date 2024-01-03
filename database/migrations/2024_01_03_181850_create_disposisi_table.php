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
        Schema::create('disposisi', function (Blueprint $table) {
            $table->id();
            $table->string('tx_number');
            $table->string('no_agenda');
            $table->text('isi_disposisi');
            $table->integer('tujuan_disposisi');
            $table->integer('status')->nullable();

            $table->foreign('tx_number')->references('tx_number')->on('surat_masuk');
            $table->foreign('tujuan_disposisi')->references('id')->on('organization');
            $table->foreign('status')->references('id')->on('m_status_disposisi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disposisi');
    }
};