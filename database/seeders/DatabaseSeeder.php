<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Master\Organization;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            AsalSuratSeeder::class,
            DerajatSuratSeeder::class,
            EntityAsalSuratSeeder::class,
            KlasifikasiSuratSeeder::class,
            StatusDisposisiSeeder::class,
            StatusSuratSeeder::class,
            OrganizationSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
            userSeeder::class,
            AddStatusSuratSeeder::class,
            AddUserSeederBagRenmin::class,
            JenisSuratSeeder::class,
        ]);
    }
}
