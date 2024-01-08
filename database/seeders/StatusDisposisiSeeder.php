<?php

namespace Database\Seeders;

use App\Models\Master\StatusDisposisi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusDisposisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StatusDisposisi::create([
            'name' => 'Approved',
            'description' => 'Surat di Approve dan ditandatangani'
        ]);

        StatusDisposisi::create([
            'name' => 'Rejected',
            'description' => 'Surat di Reject dan dikembalikan ke pengirim'
        ]);
    }
}
