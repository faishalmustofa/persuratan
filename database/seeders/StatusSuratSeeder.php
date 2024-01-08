<?php

namespace Database\Seeders;

use App\Models\Master\StatusSurat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StatusSurat::create([
            'name' => 'Diterima',
            'description' => 'Surat diterima oleh Taud'
        ]);

        StatusSurat::create([
            'name' => 'Diproses',
            'description' => 'Surat telah dilakukan print blanko disposisi dan sedang diproses'
        ]);

        StatusSurat::create([
            'name' => 'Disposisi',
            'description' => 'Surat telah dilakukan pengisian disposisi dan langsung dilakukan disposisi'
        ]);

        StatusSurat::create([
            'name' => 'Dikirim',
            'description' => 'Surat Surat dikirimkan ke tujuan disposisi'
        ]);
    }
}
