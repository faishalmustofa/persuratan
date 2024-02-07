<?php

namespace Database\Seeders;

use App\Models\Reference\JenisSuratMasuk;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JenisSuratMasukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenisSurat = array(
            [
                'jenis_surat' => 'BIASA',
                'kd_jenis' => 'B'
            ],[
                'jenis_surat' => 'NOTA DINAS',
                'kd_jenis' => 'ND'
            ],[
                'jenis_surat' => 'TELEGRAM RAHASIA',
                'kd_jenis' => 'TR'
            ],[
                'jenis_surat' => 'SURAT TELEGRAM RAHASIA',
                'kd_jenis' => 'STR'
            ],[
                'jenis_surat' => 'SURAT PERINTAH',
                'kd_jenis' => 'SPRIN'
            ],[
                'jenis_surat' => 'SURAT IJIN JALAN',
                'kd_jenis' => 'SIJ'
            ],[
                'jenis_surat' => 'PEGNADUAN MASYARAKAT',
                'kd_jenis' => 'PM'
            ],[
                'jenis_surat' => 'UNDANGAN',
                'kd_jenis' => 'UND'
            ],[
                'jenis_surat' => 'RAHASIA',
                'kd_jenis' => 'R'
            ],[
                'jenis_surat' => 'TELEGRAM',
                'kd_jenis' => 'T'
            ],
        );

        foreach($jenisSurat as $vl){
            JenisSuratMasuk::create([
                'jenis_surat' => $vl['jenis_surat'],
                'kd_jenis' => $vl['kd_jenis']
            ]);
        }
    }
}
