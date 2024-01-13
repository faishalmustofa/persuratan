<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Role::create(['name' => 'Super Admin']);
        $admin = Role::create(['name' => 'Super Admin']);
        $pimpinan = Role::create(['name' => 'Pimpinan']);
        $operator = Role::create(['name' => 'Operator']);

        $admin->givePermissionTo([
            'create-user',
            'edit-user',
            'delete-user',
            'create-surat',
            'edit-surat',
            'delete-surat',
            'update-disposisi'
        ]);

        $operator->givePermissionTo([
            'create-surat',
            'edit-surat',
            'delete-surat',
            'update-disposisi',
            'print-blanko',
            'kirim-disposisi'
        ]);
    }
}
