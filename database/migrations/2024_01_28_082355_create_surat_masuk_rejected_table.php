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
        Schema::create('surat_masuk_rejected', function (Blueprint $table) {
            $table->id();
            $table->string('tx_number');
            $table->string('image')->nullable();
            $table->string('notes');
            $table->string('rejected_by');
            $table->dateTime('rejected_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_masuk_rejected');
    }
};
