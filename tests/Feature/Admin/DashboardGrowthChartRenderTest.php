<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardGrowthChartRenderTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_renders_growth_chart_container_and_apexcharts_cdn(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('id="growthChart"', false);
        $response->assertSee('cdn.jsdelivr.net/npm/apexcharts', false);
    }
}