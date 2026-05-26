<?php

namespace Tests\Feature;

use App\Models\Vehicle;
use App\Models\VehicleView;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class VehicleApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow('2026-05-26 15:30:00');
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_show_returns_vehicle_json(): void
    {
        $vehicle = Vehicle::create([
            'make' => 'BMW',
            'model' => '520d',
            'year' => 2024,
            'price' => 64000,
        ]);

        $this->getJson("/api/vehicles/{$vehicle->id}")
            ->assertOk()
            ->assertJsonPath('id', $vehicle->id)
            ->assertJsonPath('make', 'BMW')
            ->assertJsonPath('model', '520d');
    }

    public function test_show_returns_404_for_missing_vehicle(): void
    {
        $this->getJson('/api/vehicles/99999')->assertNotFound();
    }

    public function test_show_buffers_view_counts_in_cache(): void
    {
        $vehicle = Vehicle::create([
            'make' => 'Toyota',
            'model' => 'Corolla',
            'year' => 2020,
            'price' => 12000,
        ]);

        $this->getJson("/api/vehicles/{$vehicle->id}");
        $this->getJson("/api/vehicles/{$vehicle->id}");
        $this->getJson("/api/vehicles/{$vehicle->id}");

        $cacheKey = sprintf('vehicle_views:%d:%s', $vehicle->id, '2026-05-26-15');

        $this->assertSame(3, (int) Cache::get($cacheKey));
    }

    public function test_trending_flushes_pending_views_and_returns_counts(): void
    {
        $vehicle = Vehicle::create([
            'make' => 'Audi',
            'model' => 'A4',
            'year' => 2019,
            'price' => 22000,
        ]);

        $this->getJson("/api/vehicles/{$vehicle->id}");
        $this->getJson("/api/vehicles/{$vehicle->id}");

        $this->getJson('/api/vehicles/trending')
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.id', $vehicle->id)
            ->assertJsonPath('0.view_count', 2)
            ->assertJsonPath('0.make', 'Audi');
    }

    public function test_trending_orders_by_views_desc_then_vehicle_id_asc(): void
    {
        $leader = Vehicle::create([
            'make' => 'BMW',
            'model' => 'X5',
            'year' => 2022,
            'price' => 50000,
        ]);
        $lowerId = Vehicle::create([
            'make' => 'Ford',
            'model' => 'Focus',
            'year' => 2018,
            'price' => 9000,
        ]);
        $higherId = Vehicle::create([
            'make' => 'Kia',
            'model' => 'Rio',
            'year' => 2017,
            'price' => 7000,
        ]);

        $this->assertLessThan($higherId->id, $lowerId->id);

        $bucket = Carbon::now()->startOfHour();

        VehicleView::create([
            'vehicle_id' => $leader->id,
            'bucket_hour' => $bucket,
            'view_count' => 50,
        ]);
        VehicleView::create([
            'vehicle_id' => $higherId->id,
            'bucket_hour' => $bucket,
            'view_count' => 10,
        ]);
        VehicleView::create([
            'vehicle_id' => $lowerId->id,
            'bucket_hour' => $bucket,
            'view_count' => 10,
        ]);

        $response = $this->getJson('/api/vehicles/trending')->assertOk()->json();

        $this->assertCount(3, $response);
        $this->assertSame($leader->id, $response[0]['id']);
        $this->assertSame(50, $response[0]['view_count']);
        $this->assertSame(10, $response[1]['view_count']);
        $this->assertSame(10, $response[2]['view_count']);
        $this->assertSame($lowerId->id, $response[1]['id']);
        $this->assertSame($higherId->id, $response[2]['id']);
    }
}
