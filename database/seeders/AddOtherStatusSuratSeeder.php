<?php

namespace Database\Seeders;

use App\Models\Master\StatusSurat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddOtherStatusSuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StatusSurat::create([
            'name' => 'Pindah Berkas (SPRI)',
            'description' => 'Surat sudah dilakukan pemindahan berkas dari TAUD ke SPRI KADIV PROPAM'
        ]);

        StatusSurat::create([
            'name' => 'Diterima (SPRI)',
            'description' => 'Berkas surat sudah diterima oleh SPRI KADIV'
        ]);

        StatusSurat::create([
            'name' => 'Diproses (SPRI)',
            'description' => 'Berkas surat sedang dilakukan proses oleh SPRI KADIV'
        ]);

        StatusSurat::create([
            'name' => 'Pindah Berkas (TAUD)',
            'description' => 'Berkas Surat sudah dilakukan pemindahan berkas dari SPRI KADIV PROPAM ke TAUD'
        ]);

        StatusSurat::create([
            'name' => 'Diterima (TAUD)',
            'description' => 'Berkas surat sudah diterima oleh TAUD'
        ]);

        StatusSurat::create([
            'name' => 'Diproses (TAUD)',
            'description' => 'Surat telah dilakukan print blanko oleh TAUD'
        ]);
    }
}
