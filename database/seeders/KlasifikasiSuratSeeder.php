<?php

namespace Database\Seeders;

use App\Models\Reference\KlasifikasiSurat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KlasifikasiSuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        KlasifikasiSurat::create([
            'nama' => 'Biasa'
        ]);

        KlasifikasiSurat::create([
            'nama' => 'Rahasia'
        ]);
    }
}
