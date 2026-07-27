<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnalyticsGrowthChartRenderTest extends TestCase
{
    use RefreshDatabase;

    public function test_analytics_renders_user_and_event_growth_charts(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.analytics'));

        $response->assertOk();
        $response->assertSee('id="userGrowthChart"', false);
        $response->assertSee('id="eventGrowthChart"', false);
    }
}