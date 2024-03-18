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
        Schema::table('log_surat_keluars', function (Blueprint $table) {
            $table->integer('konseptor')->nullable();
            $table->integer('penandatangan')->nullable();

            $table->foreign('konseptor')->references('id')->on('users');
            $table->foreign('penandatangan')->references('id')->on('organization');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('log_surat_keluars', function (Blueprint $table) {
            //
        });
    }
};
