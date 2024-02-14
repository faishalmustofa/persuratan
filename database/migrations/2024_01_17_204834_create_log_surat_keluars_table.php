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
        Schema::create('log_surat_keluar', function (Blueprint $table) {
            $table->id();
            $table->string('tx_number');
            $table->date('process_date');
            $table->string('status');
            $table->integer('posisi_surat');
            $table->integer('updated_by');
            $table->string('catatan')->nullable();

            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('posisi_surat')->references('id')->on('organization');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_surat_keluars');
    }
};
