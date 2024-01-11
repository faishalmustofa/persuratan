<?php

namespace Database\Seeders;

use App\Models\Master\Organization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Organization::create([
            'nama' => 'KADIV PROPAM',
            'leader_alias' => 'KADIV PROPAM',
            'parent_id' => null,
            'description' => 'KADIV PROPAM'
        ]);

        Organization::create([
            'nama' => 'TAUD',
            'leader_alias' => 'TAUD',
            'parent_id' => 1,
            'description' => 'TAUD'
        ]);

        Organization::create([
            'nama' => 'URKEU',
            'leader_alias' => 'URKEU',
            'parent_id' => 1,
            'description' => 'URKEU'
        ]);

        Organization::create([
            'nama' => 'BAGRENMIN',
            'leader_alias' => 'KABAG RENMIN',
            'parent_id' => 1,
            'description' => 'BAGRENMIN'
        ]);

        Organization::create([
            'nama' => 'SUBBAG REN',
            'parent_id' => 4,
            'description' => 'SUBBAG REN'
        ]);

        Organization::create([
            'nama' => 'SUBBAG SUMDA',
            'parent_id' => 4,
            'description' => 'SUBBAG SUMDA'
        ]);

        Organization::create([
            'nama' => 'SUBBAG BINFUNG',
            'parent_id' => 4,
            'description' => 'SUBBAG BINFUNG'
        ]);

        Organization::create([
            'nama' => 'BAGYANDUAN',
            'leader_alias' => 'KABAG YANDUAN',
            'parent_id' => 1,
            'description' => 'BAGYANDUAN'
        ]);

        Organization::create([
            'nama' => 'BAGREHABPERS',
            'leader_alias' => 'KABAG REHABPERS',
            'parent_id' => 1,
            'description' => 'BAGREHABPERS'
        ]);

        Organization::create([
            'nama' => 'BIRO PROVOST',
            'leader_alias' => 'KARO PROVOST',
            'parent_id' => 1,
            'description' => 'BIRO PROVOST'
        ]);

        Organization::create([
            'nama' => 'BIRO PAMINAL',
            'leader_alias' => 'KARO PAMINAL',
            'parent_id' => 1,
            'description' => 'BIRO PAMINAL'
        ]);

        Organization::create([
            'nama' => 'BIRO WABROF',
            'leader_alias' => 'KARO WABPROF',
            'parent_id' => 1,
            'description' => 'BIRO WABROF'
        ]);
    }
}
