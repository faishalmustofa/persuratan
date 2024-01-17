<?php

namespace Database\Seeders;

use App\Models\Master\Organization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddSpriOrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Organization::create([
            'nama' => 'SPRI',
            'leader_alias' => 'SPRI',
            'parent_id' => 1,
            'description' => 'SPRI KADIV PROPAM',
            'blanko_path' => 'blanko_karowabprof.docx',
            'suffix_agenda' => 'DIVPROPAM'
        ]);
    }
}
