<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        // Cari atau buat user owner default untuk setiap org
        // Password default: 'password' (bisa diubah setelah login pertama)
        $owner1 = User::firstOrCreate(
            ['email' => 'ketua.himasi@amikom.ac.id'],
            [
                'name' => 'Ketua HIMA SI',
                'password' => bcrypt('password'),
                'role' => 'panitia',
            ]
        );

        $owner2 = User::firstOrCreate(
            ['email' => 'ketua.bem@amikom.ac.id'],
            [
                'name' => 'Ketua BEM',
                'password' => bcrypt('password'),
                'role' => 'panitia',
            ]
        );

        $owner3 = User::firstOrCreate(
            ['email' => 'ketua.acocc@amikom.ac.id'],
            [
                'name' => 'Ketua ACO CC',
                'password' => bcrypt('password'),
                'role' => 'panitia',
            ]
        );

        $organizations = [
            [
                'name' => 'HIMA Sistem Informasi',
                'slug' => 'hima-si',
                'type' => 'hima',
                'description' => 'Himpunan Mahasiswa Program Studi Sistem Informasi Universitas Amikom Yogyakarta',
                'email' => 'hima.si@amikom.ac.id',
                'phone' => '081234567890',
                'status' => 'active',
                'commission_percentage' => 10.00,
                'approved_at' => now(),
                'bank_account_name' => 'Ketua HIMA SI',
                'bank_account_number' => '1234567890',
                'bank_name' => 'BCA',
            ],
            [
                'name' => 'BEM Universitas Amikom',
                'slug' => 'bem-amikom',
                'type' => 'bem',
                'description' => 'Badan Eksekutif Mahasiswa Universitas Amikom Yogyakarta',
                'email' => 'bem@amikom.ac.id',
                'phone' => '081234567891',
                'status' => 'active',
                'commission_percentage' => 12.00,
                'approved_at' => now(),
                'bank_account_name' => 'Ketua BEM',
                'bank_account_number' => '2345678901',
                'bank_name' => 'BRI',
            ],
            [
                'name' => 'ACO Coding Club',
                'slug' => 'aco-cc',
                'type' => 'ukm',
                'description' => 'Amikom Coding Club - Komunitas programming untuk mahasiswa',
                'email' => 'acocc@amikom.ac.id',
                'phone' => '081234567892',
                'status' => 'active',
                'commission_percentage' => 10.00,
                'approved_at' => now(),
                'bank_account_name' => 'Ketua ACO CC',
                'bank_account_number' => '3456789012',
                'bank_name' => 'Mandiri',
            ],
        ];

        foreach ($organizations as $orgData) {
            $org = Organization::updateOrCreate(
                ['slug' => $orgData['slug']],
                $orgData
            );

            // Attach owner
            $ownerEmail = match($org->slug) {
                'hima-si' => $owner1->email,
                'bem-amikom' => $owner2->email,
                'aco-cc' => $owner3->email,
            };

            $owner = User::where('email', $ownerEmail)->first();

            if ($owner && !$org->hasUser($owner->id)) {
                $org->users()->attach($owner->id, [
                    'role' => 'owner',
                    'invited_at' => now(),
                    'joined_at' => now(),
                ]);
            }
        }
    }
}
