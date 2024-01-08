<?php

namespace Database\Seeders;

use App\Models\Reference\DerajatSurat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DerajatSuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DerajatSurat::create([
            'nama' => 'Biasa'
        ]);

        DerajatSurat::create([
            'nama' => 'Derajat'
        ]);

        DerajatSurat::create([
            'nama' => 'Kilat'
        ]);
    }
}
