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
                'name' => 'Diterima',
                'description' => 'Surat telah diterima',
                'tipe_surat' => 'keluar'
            ],
            [
                'name' => 'Diajukan',
                'description' => 'Surat telah diajukan',
                'tipe_surat' => 'keluar'
            ],
            [
                'name' => 'Diteruskan',
                'description' => 'Surat telah diteruskan',
                'tipe_surat' => 'keluar'
            ],
            [
                'name' => 'Diterima (TAUD)',
                'description' => 'Surat telah diterima oleh TAUD',
                'tipe_surat' => 'keluar'
            ],
            [
                'name' => 'Dikembalikan',
                'description' => 'Surat dikembalikan dengan catatan',
                'tipe_surat' => 'keluar'
            ],
            [
                'name' => 'Ditandatangan',
                'description' => 'Surat telah ditandatangan',
                'tipe_surat' => 'keluar'
            ],
            [
                'name' => 'Diberi nomor surat',
                'description' => 'Surat telah ditandatangan',
                'tipe_surat' => 'keluar'
            ],
            [
                'name' => 'Diagendakan',
                'description' => 'Surat telah diagendakan',
                'tipe_surat' => 'keluar'
            ],
            [
                'name' => 'Dikirimkan ke tujuan',
                'description' => 'Surat telah dikirimkan ke tujuan',
                'tipe_surat' => 'keluar'
            ],
            [
                'name' => 'Sampai Tujuan',
                'description' => 'Surat telah dikirimkan ke tujuan',
                'tipe_surat' => 'keluar'
            ],
        );

        foreach ($surat_keluar as $key => $value) {
            StatusSurat::create($value);
        }
    }
}
