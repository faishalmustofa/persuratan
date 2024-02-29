<?php

namespace Database\Seeders;

use App\Models\Master\AsalSurat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AsalSuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AsalSurat::create([
            'name' => 'Internal'
        ]);

        AsalSurat::create([
            'name' => 'Eksternal'
        ]);
    }
}
