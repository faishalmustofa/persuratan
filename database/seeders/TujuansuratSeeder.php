<?php

namespace Database\Seeders;

use App\Models\Master\TujuanSurat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TujuansuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TujuanSurat::create([
            'name' => 'Internal'
        ]);

        TujuanSurat::create([
            'name' => 'Eksternal'
        ]);
    }
}
