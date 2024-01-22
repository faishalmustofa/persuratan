<?php

namespace Database\Seeders;

use App\Models\Master\StatusSurat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddStatusSuratKeluarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $surat_keluar = array(
            [
                'name' => 'Diajukan',
                'description' => 'Surat telah diajukan',
                'tipe_surat' => 'masuk'
            ],
            [
                'name' => 'Diterima',
                'description' => 'Surat telah diterima',
                'tipe_surat' => 'masuk'
            ],
            [
                'name' => 'Diteruskan',
                'description' => 'Surat telah diteruskan',
                'tipe_surat' => 'masuk'
            ],
            [
                'name' => 'Diterima (TAUD)',
                'description' => 'Surat telah diterima',
                'tipe_surat' => 'masuk'
            ],
            [
                'name' => 'Dikembalikan',
                'description' => 'Surat dikembalikan dengan catatan',
                'tipe_surat' => 'masuk'
            ],
            [
                'name' => 'Ditandatangan',
                'description' => 'Surat telah ditandatangan',
                'tipe_surat' => 'masuk'
            ],
            [
                'name' => 'Diberi nomor surat',
                'description' => 'Surat telah ditandatangan',
                'tipe_surat' => 'masuk'
            ],
        );

        foreach ($surat_keluar as $key => $value) {
            StatusSurat::create($value);
        }
    }
}
