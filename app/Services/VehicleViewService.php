<?php

namespace App\Services;

use App\Models\VehicleView;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class VehicleViewService
{
    private const DIRTY_INDEX_KEY = 'vehicle_views:dirty';

    public function recordView(int $vehicleId): void
    {
        $key = $this->cacheKey($vehicleId);

        Cache::add($key, 0, $this->ttlSeconds());
        Cache::increment($key);

        $dirty = Cache::get(self::DIRTY_INDEX_KEY, []);
        $dirty[$key] = true;
        Cache::put(self::DIRTY_INDEX_KEY, $dirty, $this->ttlSeconds());
    }

    /**
     * Batch-flush pending cache counters into hourly DB buckets.
     * Called before trending reads (and can be moved to a queue/cron in production).
     */
    public function flushPending(): void
    {
        $dirty = Cache::get(self::DIRTY_INDEX_KEY, []);

        if ($dirty === []) {
            return;
        }

        foreach (array_keys($dirty) as $key) {
            $pending = (int) Cache::pull($key);

            if ($pending < 1) {
                continue;
            }

            [$vehicleId, $bucketHour] = $this->parseCacheKey($key);

            $record = VehicleView::query()
                ->where('vehicle_id', $vehicleId)
                ->where('bucket_hour', $bucketHour)
                ->first();

            if ($record) {
                $record->increment('view_count', $pending);
            } else {
                VehicleView::create([
                    'vehicle_id' => $vehicleId,
                    'bucket_hour' => $bucketHour,
                    'view_count' => $pending,
                ]);
            }
        }

        Cache::forget(self::DIRTY_INDEX_KEY);
    }

    private function cacheKey(int $vehicleId): string
    {
        return sprintf(
            'vehicle_views:%d:%s',
            $vehicleId,
            $this->currentBucketHour()->format('Y-m-d-H')
        );
    }

    /**
     * @return array{0: int, 1: Carbon}
     */
    private function parseCacheKey(string $key): array
    {
        if (! preg_match('/^vehicle_views:(\d+):(\d{4}-\d{2}-\d{2}-\d{2})$/', $key, $matches)) {
            throw new \InvalidArgumentException("Invalid vehicle view cache key: {$key}");
        }

        return [
            (int) $matches[1],
            Carbon::createFromFormat('Y-m-d-H', $matches[2], 'UTC')->startOfHour(),
        ];
    }

    private function currentBucketHour(): Carbon
    {
        return Carbon::now('UTC')->startOfHour();
    }

    private function ttlSeconds(): int
    {
        return 60 * 60 * 48;
    }
}
