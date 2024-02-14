<?php

namespace Database\Seeders;

use App\Models\Reference\JenisSurat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JenisSuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenis_surat = array(
            [
                'nama' => 'B/KELUAR',
                'format' => 'B/KELUAR /BULANROMAWI/TAHUN',
                'deskripsi' => 'SURAT BIASA KELUAR',
            ],
            [
                'nama' => 'B/ND KELUAR',
                'format' => 'B/ND-KELUAR /BULANROMAWI/TAHUN',
                'deskripsi' => 'SURAT NOTA DINAS BIASA KELUAR',
            ],
            [
                'nama' => 'R/KELUAR',
                'format' => 'R/KELUAR /BULANROMAWI/TAHUN',
                'deskripsi' => 'SURAT RAHASIA KELUAR',
            ],
            [
                'nama' => 'R/ND KELUAR',
                'format' => 'R/ND KELUAR /BULANROMAWI/TAHUN',
                'deskripsi' => 'SURAT NOTA DINAS RAHASIA KELUAR',
            ],
            [
                'nama' => 'SPRIN/KELUAR',
                'format' => 'SPRIN/ /BULANROMAWI/TAHUN',
                'deskripsi' => 'SURAT PERINTAH',
            ],
            [
                'nama' => 'PERKADIV',
                'format' => 'PERKADIV/ /BULANROMAWI/TAHUN',
                'deskripsi' => 'SURAT PERINTAH KEPALA DIVISI',
            ],
            [
                'nama' => 'SPPP',
                'format' => 'SPPP/ /BULANROMAWI/TAHUN',
                'deskripsi' => 'SURAT',
            ],
            [
                'nama' => 'SPPP',
                'format' => 'SKAP/ /BULANROMAWI/TAHUN',
                'deskripsi' => 'SURAT',
            ],
            [
                'nama' => 'SOP',
                'format' => 'SOP/ /BULANROMAWI/TAHUN',
                'deskripsi' => 'SURAT',
            ],
        );

        foreach ($jenis_surat as $key => $value) {
            JenisSurat::create([
                'nama' => $value['nama'],
                'format' => $value['format'],
                'deskripsi' => $value['deskripsi']
            ]);
        }
        
    }
}
