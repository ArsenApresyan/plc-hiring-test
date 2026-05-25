# Test task: Trending Vehicles widget

Build a small full-stack feature: a "Trending Vehicles" widget that shows the 10 most-viewed cars in the last 24 hours, with a counter that increments each time someone opens a vehicle's detail page.

Should not take more than ~1–2 hours.

**Stack:** Laravel 11 + Vue 3 (Vite). Already scaffolded in this repo.

## Functional requirements

1. **`GET /api/vehicles/{id}`** — returns the vehicle's data and increments its view counter. **Assume this endpoint will be hit ~50 req/sec at peak.**
2. **`GET /api/vehicles/trending`** — returns the top 10 most-viewed vehicles in the last 24h, each with its view count. Tie-break: when two vehicles have equal view counts, the one with the smaller `id` ranks higher.
3. **Vue component `<TrendingVehicles />`** — calls the trending endpoint, displays the list, auto-refreshes every 30 seconds. Show a loading state and an error state.

## Constraints

- **No new packages.** Use what's already in `composer.json` / `package.json`. If you genuinely need to add something, justify it in `NOTES.md`.

## What we'll be looking at

- **View-counter implementation.** A naive `UPDATE … SET views = views + 1` on every request is the wrong answer at 50 rps. We want to see your caching / queueing / batching approach.
- **Trending query.** How it's structured and whether you cache it.
- **`vehicle_views` schema choice.** The placeholder migration is naive on purpose. One row per view? Aggregated? Redis sorted set? Your call.
- **Component structure.** Composables vs. inline logic, prop/emit boundaries, isolation of the fetch.
- **Automated tests.** At minimum, one meaningful test you'd write first (backend or frontend — your call). Broader coverage is a **significant bonus** and a strong signal of how you think about correctness, regressions, and risk. A green-on-CI test suite is the best version of this.
- **Git history.** Small focused commits beat one big "done" commit.
- **`NOTES.md`.** What you'd do differently with more time, and what tradeoffs you made.

## Out of scope

Auth, UI polish, deployment, Docker setup. A working `php artisan serve` + `npm run dev` (or `ddev npm run dev`) is plenty.

## Delivery

Push to a private GitLab/GitHub repo and share access, or send a zip. Include a one-line `README` addition with how to run it if you change the setup.
