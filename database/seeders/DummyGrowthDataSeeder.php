<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;

class DummyGrowthDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->createUserGrowthData();
        $this->createEventGrowthData();
        echo "✓ Dummy growth data seeded successfully!\n";
    }

    private function createUserGrowthData(): void
    {
        $firstNames = [
            'Ahmad', 'Budi', 'Citra', 'Deni', 'Eka', 'Fajar', 'Gita', 'Hendra',
            'Indra', 'Joko', 'Karina', 'Lina', 'Mira', 'Nico', 'Olivia', 'Putra',
            'Qidhah', 'Rani', 'Siti', 'Tono', 'Usman', 'Vina', 'Wandi', 'Yuni',
            'Zahra', 'Arif', 'Bella', 'Chandra', 'Dewi', 'Elman'
        ];

        $lastNames = [
            'Wijaya', 'Rahman', 'Santoso', 'Kusuma', 'Prasetyo', 'Handoko', 'Setiadji',
            'Hartono', 'Suryanto', 'Gunawan', 'Hermawan', 'Harianto', 'Ismail', 'Janssen',
            'Kristanto', 'Lukman', 'Mardianto', 'Nugraha', 'Ovandi', 'Pratama', 'Rachman'
        ];

        // 30 hari dengan 2-4 user per hari
        for ($day = 29; $day >= 0; $day--) {
            $userCount = rand(2, 4);
            $createdAt = now()->subDays($day)->addHours(rand(0, 23))->addMinutes(rand(0, 59));

            for ($i = 0; $i < $userCount; $i++) {
                $firstName = $firstNames[array_rand($firstNames)];
                $lastName = $lastNames[array_rand($lastNames)];
                $email = strtolower($firstName . '.' . $lastName . '-' . rand(100, 999) . '@example.com');

                User::firstOrCreate(
                    ['email' => $email],
                    [
                        'name' => "$firstName $lastName",
                        'password' => bcrypt('password'),
                        'role' => 'user',
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                    ]
                );
            }
        }

        echo "✓ " . User::where('role', 'user')->count() . " dummy users created for 30-day growth\n";
    }

    private function createEventGrowthData(): void
    {
        $eventNames = [
            'Workshop Laravel Terbaru', 'Seminar Web Development', 'Hackathon 2026',
            'Conference Cloud Computing', 'Training JavaScript Advanced', 'Webinar AI & ML',
            'Workshop React.js', 'Bootcamp Full Stack', 'Seminar DevOps', 'Tech Talk Flutter',
            'Python Workshop', 'UI/UX Design Challenge', 'Networking Tech Event', 'Startup Pitch',
            'Digital Marketing Seminar', 'Database Design Workshop', 'API Development Masterclass',
            'Mobile App Development', 'Blockchain Workshop', 'Data Science Conference',
            'E-commerce Strategy Seminar', 'SEO & SEM Workshop', 'Social Media Strategy',
            'Content Marketing Bootcamp', 'Video Production Workshop', 'Photography Masterclass',
            'Graphic Design Course', 'Animation Workshop', 'Game Development Seminar'
        ];

        $descriptions = [
            'Pelajari teknologi terbaru dalam industri IT',
            'Tingkatkan skill Anda dengan mentor berpengalaman',
            'Networking dengan profesional di bidangnya',
            'Dapatkan sertifikat resmi setelah mengikuti',
            'Pembelajaran praktis dan interaktif',
            'Materi terkini dan relevan dengan industri',
            'Kesempatan kolaborasi dengan perusahaan top',
            'Akses lifetime untuk materi pembelajaran',
        ];

        $locations = [
            'Ruang A101 - Gedung Utama', 'Auditorium Lantai 3', 'Ruang Kelas 5B',
            'Lab Komputer Blok E', 'Aula Serbaguna', 'Ruang Meeting Lantai 2',
            'Amphitheater Indoor', 'Studio Rekaman', 'Ruang Seminar VIP',
            'Convention Center', 'Learning Hub', 'Tech Lab'
        ];

        $organizations = \App\Models\Organization::where('status', 'active')->get();

        if ($organizations->isEmpty()) {
            echo "⚠ No active organizations found, skipping event creation\n";
            return;
        }

        // 30 hari dengan 1-3 event per hari
        for ($day = 29; $day >= 0; $day--) {
            $eventCount = rand(1, 3);
            $createdAt = now()->subDays($day)->addHours(rand(0, 23))->addMinutes(rand(0, 59));

            for ($i = 0; $i < $eventCount; $i++) {
                $org = $organizations->random();
                $category = \App\Models\Category::inRandomOrder()->first();

                if (!$category) {
                    continue;
                }

                $eventName = $eventNames[array_rand($eventNames)];
                $title = "{$eventName} - " . now()->addDays(rand(5, 45))->format('M d');

                Event::firstOrCreate(
                    [
                        'organization_id' => $org->id,
                        'title' => $title,
                    ],
                    [
                        'category_id' => $category->id,
                        'description' => $descriptions[array_rand($descriptions)],
                        'date' => now()->addDays(rand(5, 60)),
                        'location' => $locations[array_rand($locations)],
                        'price' => rand(50000, 500000),
                        'stock' => rand(50, 500),
                        'poster_path' => 'posters/event-' . uniqid() . '.png',
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                    ]
                );
            }
        }

        echo "✓ " . Event::count() . " total events created for 30-day growth\n";
    }
}
