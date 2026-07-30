<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data kategori default
        $categories = [
            'Seminar',
            'Entertainment',
            'Workshop',
            'Conference',
            'Sports',
        ];

        foreach ($categories as $name) {
            Category::firstOrCreate(['name' => $name], ['slug' => Str::slug($name)]);
        }
    }
}
