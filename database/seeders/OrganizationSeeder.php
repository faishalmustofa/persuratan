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
            'description' => 'KADIV PROPAM',
            'blanko_path' => 'blanko_kadiv.docx'
        ]);

        Organization::create([
            'nama' => 'TAUD',
            'leader_alias' => 'TAUD',
            'parent_id' => 1,
            'description' => 'TAUD',
            'blanko_path' => 'blanko_kadiv.docx'
        ]);

        Organization::create([
            'nama' => 'URKEU',
            'leader_alias' => 'URKEU',
            'parent_id' => 1,
            'description' => 'URKEU',
            'blanko_path' => 'blanko_kadiv.docx'
        ]);

        Organization::create([
            'nama' => 'BAGRENMIN',
            'leader_alias' => 'KABAG RENMIN',
            'parent_id' => 1,
            'description' => 'BAGRENMIN',
            'blanko_path' => 'blanko_renmin.docx'
        ]);

        Organization::create([
            'nama' => 'SUBBAG REN',
            'parent_id' => 4,
            'description' => 'SUBBAG REN',
            'blanko_path' => 'blanko_renmin.docx'
        ]);

        Organization::create([
            'nama' => 'SUBBAG SUMDA',
            'parent_id' => 4,
            'description' => 'SUBBAG SUMDA',
            'blanko_path' => 'blanko_renmin.docx'
        ]);

        Organization::create([
            'nama' => 'SUBBAG BINFUNG',
            'parent_id' => 4,
            'description' => 'SUBBAG BINFUNG',
            'blanko_path' => 'blanko_renmin.docx'
        ]);

        Organization::create([
            'nama' => 'BAGYANDUAN',
            'leader_alias' => 'KABAG YANDUAN',
            'parent_id' => 1,
            'description' => 'BAGYANDUAN',
            'blanko_path' => 'blanko_yanduan.docx'
        ]);

        Organization::create([
            'nama' => 'BAGREHABPERS',
            'leader_alias' => 'KABAG REHABPERS',
            'parent_id' => 1,
            'description' => 'BAGREHABPERS',
            'blanko_path' => 'blanko_rehabpers.docx'
        ]);

        Organization::create([
            'nama' => 'BIRO PROVOST',
            'leader_alias' => 'KARO PROVOST',
            'parent_id' => 1,
            'description' => 'BIRO PROVOST',
            'blanko_path' => 'blanko_karoprovos.docx'
        ]);

        Organization::create([
            'nama' => 'BIRO PAMINAL',
            'leader_alias' => 'KARO PAMINAL',
            'parent_id' => 1,
            'description' => 'BIRO PAMINAL',
            'blanko_path' => 'blanko_karopaminal.docx'
        ]);

        Organization::create([
            'nama' => 'BIRO WABROF',
            'leader_alias' => 'KARO WABPROF',
            'parent_id' => 1,
            'description' => 'BIRO WABROF',
            'blanko_path' => 'blanko_karowabprof.docx'
        ]);
    }
}
