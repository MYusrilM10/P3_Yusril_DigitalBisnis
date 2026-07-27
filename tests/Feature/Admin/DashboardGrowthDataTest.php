<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardGrowthDataTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_exposes_30_day_growth_arrays(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        User::factory()->count(2)->create(['role' => 'user']);
        User::factory()->create(['role' => 'panitia']);

        $category = Category::create([
            'name' => 'Musik',
            'slug' => 'musik',
        ]);

        Event::create([
            'category_id' => $category->id,
            'title' => 'Konser Uji',
            'date' => now()->addDays(5),
            'location' => 'Jakarta',
            'price' => 50000,
            'stock' => 100,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertViewHas('growthLabels', fn ($labels) => count($labels) === 30
            && end($labels) === now()->toDateString());
        $response->assertViewHas('userGrowth', fn ($counts) => count($counts) === 30
            && array_sum($counts) === 2);
        $response->assertViewHas('eventGrowth', fn ($counts) => count($counts) === 30
            && array_sum($counts) === 1);
    }
}