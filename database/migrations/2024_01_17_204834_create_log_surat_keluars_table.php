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
        Schema::create('log_surat_keluars', function (Blueprint $table) {
            $table->id();
            $table->string('tx_number');
            $table->date('process_date');
            $table->string('status');
            $table->string('catatan');
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
