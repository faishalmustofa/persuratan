<?php

namespace Database\Seeders;

use App\Models\Master\StatusSurat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddStatusSuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StatusSurat::create([
            'name' => 'Diarsipkan',
            'description' => 'Surat tidak dilanjutkan proses disposisi dan diarsipkan'
        ]);
    }
}
