<?php

namespace Database\Seeders;

use App\Models\Master\EntityAsalSurat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EntityAsalSuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EntityAsalSurat::create([
            'entity_name' => 'Internal Divpropam',
            'asal_surat_id' => 1
        ]);

        EntityAsalSurat::create([
            'entity_name' => 'Internal Mabes Polri',
            'asal_surat_id' => 1
        ]);

        EntityAsalSurat::create([
            'entity_name' => 'Polda jajaran',
            'asal_surat_id' => 1
        ]);

        EntityAsalSurat::create([
            'entity_name' => 'Lembaga Negara',
            'asal_surat_id' => 2
        ]);

        EntityAsalSurat::create([
            'entity_name' => 'Lembaga non Pemerintahan',
            'asal_surat_id' => 2
        ]);

        EntityAsalSurat::create([
            'entity_name' => 'Perseorangan',
            'asal_surat_id' => 2
        ]);

        EntityAsalSurat::create([
            'entity_name' => 'Dumas',
            'asal_surat_id' => 2
        ]);
    }
}
