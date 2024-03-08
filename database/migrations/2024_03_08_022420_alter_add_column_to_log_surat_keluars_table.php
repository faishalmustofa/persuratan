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
            $table->integer('posisi_surat');
            $table->integer('updated_by');

            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('posisi_surat')->references('id')->on('organization');
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
