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
                'name' => 'Dibuat',
                'description' => 'Surat telah dibuat',
                'tipe_surat' => 'keluar'
            ],
            [
                'name' => 'Diminta penomoran surat',
                'description' => 'Surat telah diminta nomor surat',
                'tipe_surat' => 'keluar'
            ],
            [
                'name' => 'Diterima',
                'description' => 'Surat telah diterima',
                'tipe_surat' => 'keluar'
            ],
            [
                'name' => 'Diteruskan',
                'description' => 'Surat telah diteruskan',
                'tipe_surat' => 'keluar'
            ],
            [
                'name' => 'Diberi tanda tangan',
                'description' => 'Surat telah diberi tanda tangan',
                'tipe_surat' => 'keluar'
            ],
            [
                'name' => 'Diberi nomor dan diagendakan',
                'description' => 'Surat telah diberi nomor dan diagendakan',
                'tipe_surat' => 'keluar'
            ],
            [
                'name' => 'Dikirim ke tujuan',
                'description' => 'Surat telah dikirim ke tujuan',
                'tipe_surat' => 'keluar'
            ],
            [
                'name' => 'Diterima di tujuan',
                'description' => 'Surat telah diterima di tujuan',
                'tipe_surat' => 'keluar'
            ],
            [
                'name' => 'Dikembalikan',
                'description' => 'Surat telah dikembalikan dengan catatan',
                'tipe_surat' => 'keluar'
            ],
            [
                'name' => 'Telah disesuaikan',
                'description' => 'Surat telah dikirimkan ke tujuan',
                'tipe_surat' => 'keluar'
            ],
            [
                'name' => 'Diminta penomoran surat kembali',
                'description' => 'Surat telah diminta penomoran surat kembali',
                'tipe_surat' => 'keluar'
            ],
        );

        foreach ($surat_keluar as $key => $value) {
            StatusSurat::create($value);
        }
    }
}
