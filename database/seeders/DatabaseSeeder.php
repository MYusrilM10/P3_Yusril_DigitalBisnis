<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil seeder lainnya
        $this->call([
            CategorySeeder::class,
            PartnerSeeder::class,
        ]);

        User::firstOrCreate(
            ['email' => 'superadmin@amikom.ac.id'],
            [
                'name' => 'Superadmin Amikom',
                'password' => bcrypt('password'),
                'role' => 'superadmin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin@amikom.ac.id'],
            [
                'name' => 'Admin Amikom',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]
        );

        $this->call([
            OrganizationSeeder::class,
            PlatformDummySeeder::class,
            DummyGrowthDataSeeder::class,
        ]);
    }
}    