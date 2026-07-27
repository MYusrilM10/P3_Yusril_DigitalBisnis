<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GlobalAnalyticsGrowthDataTest extends TestCase
{
    use RefreshDatabase;

    public function test_analytics_exposes_30_day_growth_arrays(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        User::factory()->count(3)->create(['role' => 'user']);

        $category = Category::create([
            'name' => 'Seminar',
            'slug' => 'seminar',
        ]);

        Event::create([
            'category_id' => $category->id,
            'title' => 'Seminar Uji',
            'date' => now()->addDays(2),
            'location' => 'Yogyakarta',
            'price' => 0,
            'stock' => 50,
        ]);

        Event::create([
            'category_id' => $category->id,
            'title' => 'Seminar Uji 2',
            'date' => now()->addDays(3),
            'location' => 'Yogyakarta',
            'price' => 0,
            'stock' => 50,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.analytics'));

        $response->assertOk();
        $response->assertViewHas('growthLabels', fn ($labels) => count($labels) === 30
            && end($labels) === now()->toDateString());
        $response->assertViewHas('userGrowth', fn ($counts) => count($counts) === 30
            && array_sum($counts) === 3);
        $response->assertViewHas('eventGrowth', fn ($counts) => count($counts) === 30
            && array_sum($counts) === 2);
    }
}