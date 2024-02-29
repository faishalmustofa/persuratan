<?php

namespace Database\Seeders;

use App\Models\Master\EntityTujuanSurat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EntityTujuanSuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EntityTujuanSurat::create([
            'entity_name' => 'Internal Divpropam',
            'tujuan_surat_id' => 1
        ]);

        EntityTujuanSurat::create([
            'entity_name' => 'Internal Mabes Polri',
            'tujuan_surat_id' => 1
        ]);

        EntityTujuanSurat::create([
            'entity_name' => 'Polda jajaran',
            'tujuan_surat_id' => 1
        ]);

        EntityTujuanSurat::create([
            'entity_name' => 'Lembaga Negara',
            'tujuan_surat_id' => 2
        ]);

        EntityTujuanSurat::create([
            'entity_name' => 'Lembaga non Pemerintahan',
            'tujuan_surat_id' => 2
        ]);

        EntityTujuanSurat::create([
            'entity_name' => 'Perseorangan',
            'tujuan_surat_id' => 2
        ]);

        EntityTujuanSurat::create([
            'entity_name' => 'Dumas',
            'tujuan_surat_id' => 2
        ]);
    }
}
