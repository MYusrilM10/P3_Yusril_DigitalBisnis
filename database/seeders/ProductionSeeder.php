<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Event;
use App\Models\Organization;
use App\Models\Review;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Lean production seed: same structure as the local dev seeders (orgs,
 * categories, admin accounts) but a small, demo-sized amount of data
 * instead of the heavy local dummy set (10-15 events, ~15 growth users).
 *
 * Run once on a fresh production database:
 *   php artisan db:seed --class=ProductionSeeder
 */
class ProductionSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([CategorySeeder::class, PartnerSeeder::class]);

        $superadmin = User::firstOrCreate(
            ['email' => 'superadmin@amikom.ac.id'],
            ['name' => 'Superadmin Amikom', 'password' => bcrypt('password'), 'role' => 'superadmin']
        );

        $admin = User::firstOrCreate(
            ['email' => 'admin@amikom.ac.id'],
            ['name' => 'Admin Amikom', 'password' => bcrypt('password'), 'role' => 'admin']
        );

        // ============ ORGANIZATION OWNERS ============
        $owners = [
            'hima-si' => User::firstOrCreate(['email' => 'ketua.himasi@amikom.ac.id'], ['name' => 'Ketua HIMA SI', 'password' => bcrypt('password'), 'role' => 'panitia']),
            'bem-amikom' => User::firstOrCreate(['email' => 'ketua.bem@amikom.ac.id'], ['name' => 'Ketua BEM', 'password' => bcrypt('password'), 'role' => 'panitia']),
            'aco-cc' => User::firstOrCreate(['email' => 'ketua.acocc@amikom.ac.id'], ['name' => 'Ketua ACO CC', 'password' => bcrypt('password'), 'role' => 'panitia']),
            'mayapala-amikom' => User::firstOrCreate(['email' => 'ketua.mayapala@amikom.ac.id'], ['name' => 'Ketua Mayapala', 'password' => bcrypt('password'), 'role' => 'panitia']),
            'hmif-amikom' => User::firstOrCreate(['email' => 'ketua.hmif@amikom.ac.id'], ['name' => 'Ketua HMIF', 'password' => bcrypt('password'), 'role' => 'panitia']),
        ];

        // ============ ORGANIZATIONS: 5 active (with real logos) + 2 pending ============
        $activeOrgDefs = [
            ['slug' => 'hima-si', 'name' => 'HIMA Sistem Informasi', 'type' => 'hima', 'logo' => 'organizations/hima-si.png', 'desc' => 'Himpunan Mahasiswa Program Studi Sistem Informasi Universitas Amikom Yogyakarta'],
            ['slug' => 'bem-amikom', 'name' => 'BEM Universitas Amikom', 'type' => 'bem', 'logo' => 'organizations/bem.jpg', 'desc' => 'Badan Eksekutif Mahasiswa Universitas Amikom Yogyakarta'],
            ['slug' => 'aco-cc', 'name' => 'ACO Coding Club', 'type' => 'ukm', 'logo' => 'organizations/aco-cc.jpg', 'desc' => 'Amikom Coding Club - Komunitas programming untuk mahasiswa'],
            ['slug' => 'mayapala-amikom', 'name' => 'Mayapala', 'type' => 'ukm', 'logo' => 'organizations/mayapala.jpg', 'desc' => 'Unit Kegiatan Mahasiswa Pecinta Alam Universitas Amikom Yogyakarta.'],
            ['slug' => 'hmif-amikom', 'name' => 'HMIF', 'type' => 'hima', 'logo' => 'organizations/hmif.jpg', 'desc' => 'Himpunan Mahasiswa Informatika Universitas Amikom Yogyakarta.'],
        ];

        $activeOrgs = [];
        foreach ($activeOrgDefs as $i => $def) {
            $org = Organization::updateOrCreate(
                ['slug' => $def['slug']],
                [
                    'name' => $def['name'],
                    'type' => $def['type'],
                    'description' => $def['desc'],
                    'logo_path' => $def['logo'],
                    'email' => "{$def['slug']}@amikom.ac.id",
                    'phone' => '08123456' . str_pad((string) ($i + 1), 4, '0', STR_PAD_LEFT),
                    'address' => 'Kampus Amikom Yogyakarta',
                    'status' => 'active',
                    'commission_percentage' => 10.00 + $i,
                    'approved_at' => now()->subDays(10),
                    'approved_by' => $admin->id,
                    'bank_account_name' => $def['name'],
                    'bank_account_number' => '9876543210' . $i,
                    'bank_name' => 'Mandiri',
                ]
            );
            $activeOrgs[] = $org;

            $owner = $owners[$def['slug']];
            if (! $org->hasUser($owner->id)) {
                $org->users()->attach($owner->id, ['role' => 'owner', 'invited_at' => now(), 'joined_at' => now()]);
            }
        }

        $pendingOrgDefs = [
            ['slug' => 'ukm-basket-amikom', 'name' => 'UKM Basket Amikom', 'type' => 'ukm', 'desc' => 'Unit Kegiatan Mahasiswa Basket Universitas Amikom Yogyakarta.'],
            ['slug' => 'komunitas-film-amikom', 'name' => 'Komunitas Film Amikom', 'type' => 'ukm', 'desc' => 'Komunitas mahasiswa yang bergerak di bidang perfilman dan videografi.'],
        ];
        foreach ($pendingOrgDefs as $def) {
            Organization::updateOrCreate(
                ['slug' => $def['slug']],
                [
                    'name' => $def['name'],
                    'type' => $def['type'],
                    'description' => $def['desc'],
                    'email' => "{$def['slug']}@amikom.ac.id",
                    'phone' => '081234509999',
                    'address' => 'Kampus Amikom Yogyakarta',
                    'status' => 'pending',
                    'commission_percentage' => 10.00,
                    'bank_account_name' => $def['name'],
                    'bank_account_number' => '1234500000',
                    'bank_name' => 'BCA',
                ]
            );
        }

        // ============ EVENTS: 12 events spread across the 5 active orgs ============
        $categories = Category::all();
        $eventNamePool = [
            'Seminar Nasional Teknologi Digital', 'Workshop Desain UI/UX', 'Talkshow Karir & Dunia Kerja',
            'Kompetisi Coding Antar Mahasiswa', 'Konser Amal Kampus', 'Festival Musik Kampus',
            'Bazaar UMKM Kampus', 'Turnamen E-Sport Kampus', 'Pelatihan Public Speaking',
            'Pameran Karya Mahasiswa', 'Workshop Fotografi', 'Seminar Kewirausahaan',
        ];

        $events = [];
        foreach ($eventNamePool as $i => $eventName) {
            $org = $activeOrgs[$i % count($activeOrgs)];
            $category = $categories->random();

            $event = Event::firstOrCreate(
                ['organization_id' => $org->id, 'title' => "{$eventName} - {$org->name}"],
                [
                    'category_id' => $category->id,
                    'description' => "{$eventName} yang diselenggarakan oleh {$org->name}. Acara ini terbuka untuk seluruh mahasiswa Amikom.",
                    'date' => now()->addDays(rand(3, 45)),
                    'location' => 'Kampus Amikom Yogyakarta - Ruang ' . chr(65 + ($i % 5)),
                    'price' => rand(30000, 150000),
                    'stock' => rand(50, 200),
                ]
            );
            $events[] = $event;
        }

        // ============ TRANSACTIONS: a handful of success + pending ============
        $buyerNames = ['Ahmad Fauzi Rahman', 'Siti Nur Azizah', 'Bagus Prasetyo', 'Rina Marlina', 'Dimas Adi Nugroho'];
        $buyers = [];
        foreach ($buyerNames as $i => $name) {
            $buyers[] = User::firstOrCreate(
                ['email' => 'buyer' . ($i + 1) . '@example.com'],
                ['name' => $name, 'password' => bcrypt('password'), 'role' => 'user']
            );
        }

        $transactions = [];
        foreach (array_slice($events, 0, 8) as $idx => $event) {
            $buyer = $buyers[$idx % count($buyers)];
            $totalPrice = $event->price + 5000;
            $platformFee = (int) round($totalPrice * 0.1);

            $status = $idx < 5 ? 'success' : 'pending';

            $transaction = Transaction::firstOrCreate(
                ['order_id' => 'ORD-' . strtoupper(uniqid())],
                [
                    'event_id' => $event->id,
                    'organization_id' => $event->organization_id,
                    'user_id' => $buyer->id,
                    'customer_name' => $buyer->name,
                    'customer_email' => $buyer->email,
                    'customer_phone' => '08123456789' . $idx,
                    'total_price' => $totalPrice,
                    'platform_fee' => $status === 'success' ? $platformFee : 0,
                    'net_income' => $status === 'success' ? $totalPrice - $platformFee : 0,
                    'status' => $status,
                    'created_at' => now()->subDays(rand(1, 20)),
                ]
            );
            $transactions[] = $transaction;
        }

        // ============ REVIEWS on success transactions ============
        $reviewTexts = [
            'Event ini sangat luar biasa! Terima kasih.',
            'Acara yang well-organized dan profesional.',
            'Presentasi yang menarik dan informatif.',
            'Rekomendasi untuk teman-teman saya.',
        ];
        foreach (array_filter($transactions, fn ($t) => $t->status === 'success') as $trx) {
            Review::firstOrCreate(
                ['user_id' => $trx->user_id, 'event_id' => $trx->event_id],
                [
                    'transaction_id' => $trx->id,
                    'rating' => rand(4, 5),
                    'title' => "Review dari {$trx->customer_name}",
                    'review_text' => $reviewTexts[array_rand($reviewTexts)],
                    'is_verified_purchase' => true,
                    'helpful_count' => rand(0, 5),
                    'created_at' => $trx->created_at->addDays(rand(1, 3)),
                ]
            );
        }

        // ============ GROWTH DATA: ~15 users + a few events spread over 30 days ============
        $firstNames = ['Ahmad', 'Budi', 'Citra', 'Deni', 'Eka', 'Fajar', 'Gita', 'Hendra', 'Indra', 'Joko', 'Karina', 'Lina', 'Mira', 'Nico', 'Olivia'];
        $lastNames = ['Wijaya', 'Rahman', 'Santoso', 'Kusuma', 'Prasetyo', 'Handoko', 'Hartono', 'Gunawan'];

        for ($i = 0; $i < 15; $i++) {
            $day = rand(0, 29);
            $createdAt = now()->subDays($day)->addHours(rand(0, 23));
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];

            User::firstOrCreate(
                ['email' => strtolower($firstName . '.' . $lastName . '-' . rand(100, 999) . '@example.com')],
                [
                    'name' => "$firstName $lastName",
                    'password' => bcrypt('password'),
                    'role' => 'user',
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]
            );
        }

        for ($i = 0; $i < 6; $i++) {
            $day = rand(0, 29);
            $createdAt = now()->subDays($day)->addHours(rand(0, 23));
            $org = $activeOrgs[array_rand($activeOrgs)];
            $category = $categories->random();
            $eventName = $eventNamePool[array_rand($eventNamePool)];

            Event::firstOrCreate(
                ['organization_id' => $org->id, 'title' => "{$eventName} - " . now()->addDays(rand(5, 45))->format('M d')],
                [
                    'category_id' => $category->id,
                    'description' => "{$eventName} yang diselenggarakan oleh {$org->name}.",
                    'date' => now()->addDays(rand(5, 45)),
                    'location' => 'Kampus Amikom Yogyakarta',
                    'price' => rand(30000, 150000),
                    'stock' => rand(50, 150),
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]
            );
        }

        $this->command->info('Production seed done: 5 active org + 2 pending, ' . count($events) . '+6 events, ' . count($transactions) . ' transactions, 15 growth users.');
    }
}
