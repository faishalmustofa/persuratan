<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'username' => 'admin',
            'password' => Hash::make('Propam12345'),
            'organization' => 1,
            'jabatan' => 'SUPER ADMIN'
        ]);
        $admin->assignRole('Super Admin');

        $taud = User::create([
            'name' => 'TAUD',
            'email' => 'taud@divpropam.polri.go.id',
            'username' => 'taud',
            'password' => Hash::make('Propam12345'),
            'organization' => 1,
            'jabatan' => 'TAUD DIVPROPAM POLRI'
        ]);

        $taud->assignRole('Operator');

        $kadiv = User::create([
            'name' => 'KADIV',
            'email' => 'kadiv@divpropam.polri.go.id',
            'username' => 'kadiv',
            'password' => Hash::make('Propam12345'),
            'organization' => 1,
            'jabatan' => 'KADIV DIVPROPAM POLRI'
        ]);

        $kadiv->assignRole('Pimpinan');
        
        $spri = User::create([
            'name' => 'SPRI KADIVPROPAM',
            'email' => 'spri_kadiv@divpropam.polri.go.id',
            'username' => 'sprikadiv',
            'password' => Hash::make('Propam12345'),
            'organization' => 1,
            'jabatan' => 'SPRI KADIDIVPROPAM POLRI'
        ]);

        $spri->assignRole('Pimpinan');
    }
}
