<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Event;
use App\Models\Organization;
use App\Models\Payout;
use App\Models\Review;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class PlatformDummySeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@amikom.ac.id')->first();
        $superadmin = User::where('email', 'superadmin@amikom.ac.id')->first();

        // ============ USERS: Biasa + Panitia Tambahan ============
        $regularUserNames = [
            'Ahmad Fauzi Rahman',
            'Siti Nur Azizah',
            'Bagus Prasetyo',
            'Rina Marlina',
            'Dimas Adi Nugroho',
        ];
        $regularUsers = [];
        foreach ($regularUserNames as $i => $name) {
            $i++;
            $user = User::firstOrCreate(
                ['email' => "user{$i}@example.com"],
                [
                    'name' => $name,
                    'password' => bcrypt('password'),
                    'role' => 'user',
                ]
            );
            $regularUsers[] = $user;
        }

        // Panitia tambahan untuk org lain
        $panitiaUserNames = [
            'Andi Setiawan',
            'Maya Kusuma Wardani',
            'Reza Firmansyah',
        ];
        $panitiaUsers = [];
        foreach ($panitiaUserNames as $i => $name) {
            $i++;
            $user = User::firstOrCreate(
                ['email' => "panitia{$i}@amikom.ac.id"],
                [
                    'name' => $name,
                    'password' => bcrypt('password'),
                    'role' => 'panitia',
                ]
            );
            $panitiaUsers[] = $user;
        }

        // ============ ORGANIZATIONS: Pending + Active ============
        $categories = Category::all();
        $organizations = Organization::all();

        // Bikin org pending (belum approve) jika belum ada
        $pendingOrgDefs = [
            ['slug' => 'ukm-fotografi', 'name' => 'UKM Fotografi Amikom', 'desc' => 'Unit Kegiatan Mahasiswa Fotografi Universitas Amikom Yogyakarta.'],
            ['slug' => 'komunitas-robotika', 'name' => 'Komunitas Robotika Amikom', 'desc' => 'Komunitas mahasiswa yang bergerak di bidang robotika dan IoT.'],
        ];
        $pendingOrgs = [];
        foreach ($pendingOrgDefs as $i => $def) {
            $i++;
            $org = Organization::firstOrCreate(
                ['slug' => $def['slug']],
                [
                    'name' => $def['name'],
                    'type' => 'ukm',
                    'description' => $def['desc'],
                    'email' => "{$def['slug']}@amikom.ac.id",
                    'phone' => "081234567{$i}00",
                    'address' => "Kampus Amikom Yogyakarta",
                    'status' => 'pending',
                    'commission_percentage' => 10.00 + $i,
                    'bank_account_name' => $def['name'],
                    'bank_account_number' => "123456789{$i}",
                    'bank_name' => 'BCA',
                ]
            );
            $pendingOrgs[] = $org;

            // Attach owner jika belum ada
            if (!$org->hasUser($panitiaUsers[$i - 1]->id)) {
                $org->users()->attach($panitiaUsers[$i - 1]->id, [
                    'role' => 'owner',
                    'invited_at' => now(),
                    'joined_at' => now(),
                ]);
            }
        }

        // Bikin org active dengan approval
        $activeOrgDefs = [
            ['slug' => 'panitia-amikom-festival', 'name' => 'Panitia Amikom Festival', 'desc' => 'Panitia penyelenggara Amikom Festival, acara tahunan kampus.'],
            ['slug' => 'panitia-wisuda-amikom', 'name' => 'Panitia Wisuda Amikom', 'desc' => 'Panitia penyelenggara acara wisuda dan seremoni kampus.'],
        ];
        $activeOrgs = [];
        foreach ($activeOrgDefs as $i => $def) {
            $i++;
            $org = Organization::firstOrCreate(
                ['slug' => $def['slug']],
                [
                    'name' => $def['name'],
                    'type' => 'kepanitiaan',
                    'description' => $def['desc'],
                    'email' => "{$def['slug']}@amikom.ac.id",
                    'phone' => "081234567{$i}11",
                    'address' => "Kampus Amikom Yogyakarta",
                    'status' => 'active',
                    'commission_percentage' => 12.00 + $i,
                    'approved_at' => now()->subDays(10),
                    'approved_by' => $admin->id,
                    'bank_account_name' => $def['name'],
                    'bank_account_number' => "987654321{$i}",
                    'bank_name' => 'Mandiri',
                ]
            );
            $activeOrgs[] = $org;

            // Attach owner
            $ownerUser = $i === 1 ? $panitiaUsers[0] : $panitiaUsers[1];
            if (!$org->hasUser($ownerUser->id)) {
                $org->users()->attach($ownerUser->id, [
                    'role' => 'owner',
                    'invited_at' => now()->subDays(15),
                    'joined_at' => now()->subDays(15),
                ]);
            }
        }

        // ============ EVENTS ============
        $eventNamePool = [
            'Seminar Nasional Teknologi Digital',
            'Workshop Desain UI/UX',
            'Talkshow Karir & Dunia Kerja',
            'Kompetisi Coding Antar Mahasiswa',
            'Konser Amal Kampus',
            'Festival Musik Kampus',
            'Bazaar UMKM Kampus',
            'Turnamen E-Sport Kampus',
            'Pelatihan Public Speaking',
            'Pameran Karya Mahasiswa',
        ];
        $allEvents = [];

        // Events dari active organizations
        foreach ($activeOrgs as $idx => $org) {
            for ($i = 1; $i <= 3; $i++) {
                $category = $categories->random();
                $eventName = $eventNamePool[array_rand($eventNamePool)];
                $event = Event::firstOrCreate(
                    [
                        'organization_id' => $org->id,
                        'title' => "{$eventName} - {$org->name}",
                    ],
                    [
                        'category_id' => $category->id,
                        'description' => "{$eventName} yang diselenggarakan oleh {$org->name}. Acara ini terbuka untuk seluruh mahasiswa Amikom.",
                        'date' => now()->addDays(rand(5, 30)),
                        'location' => "Ruang Amikom Yogyakarta - {$i}",
                        'price' => rand(30000, 150000),
                        'stock' => rand(50, 200),
                        'poster_path' => "posters/event-{$org->id}-{$i}.png",
                    ]
                );
                $allEvents[] = $event;
            }
        }

        // Events dari existing active organizations (untuk transaksi)
        foreach ($organizations->where('status', 'active')->take(2) as $org) {
            for ($i = 1; $i <= 2; $i++) {
                $category = $categories->random();
                $eventName = $eventNamePool[array_rand($eventNamePool)];
                $event = Event::firstOrCreate(
                    [
                        'organization_id' => $org->id,
                        'title' => "{$eventName} - {$org->name}",
                    ],
                    [
                        'category_id' => $category->id,
                        'description' => "{$eventName} yang diselenggarakan oleh {$org->name}.",
                        'date' => now()->addDays(rand(1, 20)),
                        'location' => "Ruang Utama Amikom",
                        'price' => rand(40000, 120000),
                        'stock' => rand(60, 150),
                        'poster_path' => "posters/extra-event-{$org->id}-{$i}.png",
                    ]
                );
                $allEvents[] = $event;
            }
        }

        // ============ TRANSACTIONS ============
        $allTransactions = [];
        $allEventsCollection = collect($allEvents);

        // Success transactions (untuk ada review & payout)
        foreach ($allEventsCollection->take(6) as $idx => $event) {
            for ($j = 0; $j < rand(2, 4); $j++) {
                $user = $regularUsers[$j % count($regularUsers)];
                $totalPrice = $event->price;
                $platformFee = round($totalPrice * 0.1, 2);
                $netIncome = $totalPrice - $platformFee;

                $transaction = Transaction::firstOrCreate(
                    [
                        'order_id' => "ORD-" . strtoupper(uniqid()),
                    ],
                    [
                        'event_id' => $event->id,
                        'organization_id' => $event->organization_id,
                        'user_id' => $user->id,
                        'customer_name' => $user->name,
                        'customer_email' => $user->email,
                        'customer_phone' => '081234567890',
                        'total_price' => $totalPrice,
                        'platform_fee' => $platformFee,
                        'net_income' => $netIncome,
                        'status' => 'success',
                        'snap_token' => null,
                        'created_at' => now()->subDays(rand(1, 15)),
                    ]
                );
                $allTransactions[] = $transaction;
            }
        }

        // Pending transactions
        foreach ($allEventsCollection->slice(6)->take(3) as $event) {
            $user = $regularUsers[0];
            $totalPrice = $event->price;

            Transaction::firstOrCreate(
                [
                    'order_id' => "ORD-PENDING-" . strtoupper(uniqid()),
                ],
                [
                    'event_id' => $event->id,
                    'organization_id' => $event->organization_id,
                    'user_id' => $user->id,
                    'customer_name' => $user->name,
                    'customer_email' => $user->email,
                    'customer_phone' => '081234567891',
                    'total_price' => $totalPrice,
                    'platform_fee' => 0,
                    'net_income' => 0,
                    'status' => 'pending',
                    'created_at' => now()->subDays(2),
                ]
            );
        }

        // ============ REVIEWS ============
        // Reviews dari success transactions
        $ratings = [5, 5, 4, 4, 4, 3, 3];
        $reviewTexts = [
            'Event ini sangat luar biasa! Sekali lagi terima kasih.',
            'Bagus, tapi agak terlalu crowded.',
            'Presentasi yang menarik dan informatif.',
            'Acara yang well-organized dan profesional.',
            'Niat bagus, tapi perlu improvement di area parking.',
            'Rekomendasi untuk teman-teman saya.',
            'Cukup memuaskan sesuai harga yang dibayarkan.',
        ];

        $allTransactionsCollection = collect($allTransactions);
        foreach ($allTransactionsCollection->take(10) as $transaction) {
            $rating = $ratings[array_rand($ratings)];
            $reviewText = $reviewTexts[array_rand($reviewTexts)];

            Review::firstOrCreate(
                [
                    'user_id' => $transaction->user_id,
                    'event_id' => $transaction->event_id,
                ],
                [
                    'transaction_id' => $transaction->id,
                    'rating' => $rating,
                    'title' => "Review dari {$transaction->customer_name}",
                    'review_text' => $reviewText,
                    'is_verified_purchase' => true,
                    'helpful_count' => rand(0, 5),
                    'created_at' => $transaction->created_at->addDays(rand(2, 10)),
                ]
            );
        }

        // ============ PAYOUTS ============
        // Payouts dengan berbagai status
        foreach ($activeOrgs as $org) {
            // Requested payout
            Payout::firstOrCreate(
                [
                    'organization_id' => $org->id,
                    'status' => 'requested',
                    'period_start' => now()->subMonth()->startOfMonth(),
                    'period_end' => now()->subMonth()->endOfMonth(),
                ],
                [
                    'amount' => rand(1000000, 5000000),
                    'notes' => "Payout request dari {$org->name}",
                    'requested_by' => $org->users()->first()->id,
                    'requested_at' => now()->subDays(3),
                ]
            );

            // Approved payout
            Payout::firstOrCreate(
                [
                    'organization_id' => $org->id,
                    'status' => 'approved',
                    'period_start' => now()->subMonths(2)->startOfMonth(),
                    'period_end' => now()->subMonths(2)->endOfMonth(),
                ],
                [
                    'amount' => rand(800000, 4000000),
                    'notes' => "Sudah disetujui oleh admin",
                    'requested_by' => $org->users()->first()->id,
                    'requested_at' => now()->subDays(10),
                    'processed_by' => $admin->id,
                    'processed_at' => now()->subDays(5),
                ]
            );

            // Paid payout
            Payout::firstOrCreate(
                [
                    'organization_id' => $org->id,
                    'status' => 'paid',
                    'period_start' => now()->subMonths(3)->startOfMonth(),
                    'period_end' => now()->subMonths(3)->endOfMonth(),
                ],
                [
                    'amount' => rand(500000, 3000000),
                    'notes' => "Sudah ditransfer ke rekening bank",
                    'requested_by' => $org->users()->first()->id,
                    'requested_at' => now()->subDays(25),
                    'processed_by' => $admin->id,
                    'processed_at' => now()->subDays(20),
                ]
            );
        }

        // Payouts untuk pending orgs (rejected)
        foreach ($pendingOrgs as $org) {
            Payout::firstOrCreate(
                [
                    'organization_id' => $org->id,
                    'status' => 'rejected',
                ],
                [
                    'amount' => rand(1000000, 2000000),
                    'notes' => "Ditolak karena organisasi masih pending approval",
                    'requested_by' => $org->users()->first()->id,
                    'requested_at' => now()->subDays(7),
                    'processed_by' => $admin->id,
                    'processed_at' => now()->subDays(5),
                ]
            );
        }

        $this->command->info('✓ Platform dummy data seeded successfully!');
    }
}
