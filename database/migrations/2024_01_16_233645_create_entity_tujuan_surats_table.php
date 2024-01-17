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
        Schema::create('m_entity_tujuan_surat', function (Blueprint $table) {
            $table->id();
            $table->string('entity_name');
            $table->integer('tujuan_surat_id');

            $table->foreign('tujuan_surat_id')->references('id')->on('m_tujuan_surat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_entity_tujuan_surat');
    }
};
