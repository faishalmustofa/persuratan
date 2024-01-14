<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AddUserSeederBagRenmin extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $renmin = User::create([
            'name' => 'KABAG RENMIN',
            'email' => 'kabagrenmin@divpropam.polri.go.id',
            'username' => 'kabagrenmin',
            'password' => Hash::make('Propam12345'),
            'organization' => 4,
            'jabatan' => 'KABAG RENMIN DIVPROPAM pOLRI'
        ]);

        $renmin->assignRole('Pimpinan');

        $urmin = User::create([
            'name' => 'URMIN Renemin',
            'email' => 'urminrenmin@divpropam.polri.go.id',
            'username' => 'urminrenmin',
            'password' => Hash::make('Propam12345'),
            'organization' => 4,
            'jabatan' => 'URMIN RENMIN DIVPROPAM pOLRI'
        ]);

        $urmin->assignRole('Operator');

        $subbag = User::create([
            'name' => 'Subbag Binfung',
            'email' => 'subbagbinfung@divpropam.polri.go.id',
            'username' => 'subbagbinfung',
            'password' => Hash::make('Propam12345'),
            'organization' => 7,
            'jabatan' => 'SUBBAG BINFUNG DIVPROPAM pOLRI'
        ]);

        $subbag->assignRole('Operator');
    }
}
