# Notes

## Approach

- **View counter:** `GET /api/vehicles/{id}` calls `VehicleViewService::recordView()`. Each hit does an atomic `Cache::increment()` on key `vehicle_views:{vehicleId}:{hour}` (UTC hour bucket). No per-request `UPDATE` on the database. Pending keys are tracked in a `vehicle_views:dirty` index for batch flush.
- **Flush:** `flushPending()` merges cached counts into `vehicle_views` (hourly rows: `vehicle_id`, `bucket_hour`, `view_count`) using increment-or-create. Called at the start of `getTrending()` so reads see up-to-date totals without flushing on every show request.
- **Trending query:** Sum `view_count` for buckets in the last 24 hours, `GROUP BY vehicle_id`, order by total views DESC then `vehicle_id` ASC (tie-break), limit 10. Join vehicle fields in PHP after the aggregate query.
- **Component:** `useTrendingVehicles` composable handles fetch, 30s polling, and cleanup on unmount. `TrendingVehicles.vue` handles display states only. Root `App.vue` mounts the page (empty `#app` in Blade — mounting an empty Vue app would replace static HTML and leave a blank page).

## Tradeoffs / what I'd do with more time

- **Flush timing:** Flush runs on trending reads only, not on a schedule. In production I'd add a queue job or cron every 30–60s so counts persist even if nobody opens trending. Alternative: Redis `INCR` with async workers writing to DB.
- **Dirty index:** Storing dirty keys in a single cache array is simple but not ideal under high concurrency (read-modify-write race). Production: Redis `SET` per key or a Redis set of dirty keys with `SADD`.
- **Hourly buckets:** Chosen over one-row-per-view for storage and 24h aggregation. Tradeoff: ~1 hour granularity (acceptable for “last 24h trending”).
- **Trending cache:** No response cache yet. With 30s frontend polling, a short TTL (e.g. 15–25s) on the trending JSON would reduce DB load.
- **Redis:** Task allows existing stack only. Used Laravel `Cache` (database/array in tests). Production would set `CACHE_STORE=redis` for `increment()` at ~50 rps.
- **Tests:** Feature tests cover show, cache buffering, flush-on-trending, and tie-break. Would add load test or integration test with Redis if this were production-bound.

## Anything I'd flag as risky in production

- **Lost counts on cache eviction** before flush (mitigate: Redis persistence, scheduled flush job).
- **Multi-instance deploys** need a shared cache (not file/array cache); otherwise each node has separate counters.
- **Clock / timezone:** Buckets use UTC hours; all app servers must agree on timezone for bucket keys.
- **Dirty index races** under parallel requests could drop a dirty key (low probability at test scale, worth fixing for real traffic).
- **SQLite** is fine for this test; production should use MySQL/PostgreSQL with indexes on `(bucket_hour)` and unique `(vehicle_id, bucket_hour)`.

## Run / test

```bash
cp .env.example .env && composer install && php artisan key:generate
touch database/database.sqlite && php artisan migrate --seed
npm install && npm run build   # or: npm run dev + php artisan serve (two terminals)
php artisan serve
php artisan test
```

Tested locally on PHP 8.5; `config/database.php` includes a small PDO constant compatibility fix for that version.
