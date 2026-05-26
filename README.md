# PLC Group — Trending Vehicles (Test Assignment)

**Candidate:** Arsen Apresyan  
**Repository:** https://github.com/ArsenApresyan/plc-hiring-test  
**Original brief:** [TASK.md](TASK.md)  
**Technical deep-dive:** [NOTES.md](NOTES.md) (for your tech lead)

---

## What was built (plain summary)

This project adds a **“Trending vehicles”** feature to a small Laravel + Vue demo app:

1. Opening a vehicle page **counts a view** (designed for high traffic — uses cache, not a slow database write on every click).
2. A **trending list** shows the **top 10 most-viewed vehicles in the last 24 hours**, with view counts.
3. The homepage widget **refreshes every 30 seconds** and shows loading / error states.

All requirements from `TASK.md` are implemented. Automated API tests are included (`php artisan test`).

---

## AI tools disclosure

I used AI assistants (e.g. Cursor) as a **coding aid** on parts of this assignment:

| Area | How AI was used |
|------|------------------|
| **Vue frontend** (`TrendingVehicles.vue`, `useTrendingVehicles.js`, `App.vue`) | Structure, composable pattern, polling/cleanup — I reviewed and tested everything in the browser. |
| **PHP / Laravel backend** | Mostly written and reasoned through by me; AI occasionally helped with boilerplate or wording. |
| **Tests & docs** | AI helped draft test cases and README text; I verified tests pass locally. |

**Important:** I understand the solution end-to-end and can explain it in a technical interview. Backend design choices (cache buffering, hourly buckets, flush strategy) were made intentionally — see [NOTES.md](NOTES.md).

---

## Easiest way to review (no terminal)

Ask a developer on your team to run the setup below, then open:

**http://127.0.0.1:8000**

They should see:

- Page title: “Hiring test starter”
- Section: **Trending vehicles** with a list (make, model, year, view count)
- List updates every 30 seconds

To generate sample data, open a few vehicle URLs (or run the curl commands in the setup section), then refresh the homepage.

---

## Setup instructions (for a developer reviewing this repo)

**Requirements:** PHP 8.2+, [Composer](https://getcomposer.org), Node.js 20+, npm.

### 1. Clone and enter the project

```bash
git clone https://github.com/ArsenApresyan/plc-hiring-test.git
cd plc-hiring-test
```

### 2. Install dependencies

```bash
cp .env.example .env
composer install
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
npm install
npm run build
```

> `npm run build` creates frontend assets so only one server process is needed.  
> **Alternative (development):** run `npm run dev` in one terminal and `php artisan serve` in another (see below).

### 3. Start the application

```bash
php artisan serve
```

Open **http://127.0.0.1:8000** in a browser.

### 4. (Optional) Development mode with hot reload

Terminal 1:

```bash
npm run dev
```

Terminal 2:

```bash
php artisan serve
```

If the page is blank, ensure `npm run dev` is running **or** use `npm run build` and remove the file `public/hot` if it exists.

### 5. Run automated tests

```bash
php artisan test
```

Expected: all tests pass (API show + trending + view counter behavior). On PHP 8.5 you may see deprecation notices from Laravel’s vendor config; exit code should still be `0`.

---

## Quick API check (optional)

With `php artisan serve` running:

| Action | URL |
|--------|-----|
| View one vehicle (increments counter) | http://127.0.0.1:8000/api/vehicles/1 |
| Trending JSON | http://127.0.0.1:8000/api/vehicles/trending |

Example — generate a few views, then check trending:

```bash
curl -s http://127.0.0.1:8000/api/vehicles/1 > /dev/null
curl -s http://127.0.0.1:8000/api/vehicles/1 > /dev/null
curl -s http://127.0.0.1:8000/api/vehicles/2 > /dev/null
curl -s http://127.0.0.1:8000/api/vehicles/trending
```

---

## What to look at in the code (for tech review)

| Topic | Location |
|-------|----------|
| View counter + trending logic | `app/Services/VehicleViewService.php` |
| API endpoints | `app/Http/Controllers/VehicleController.php` |
| Hourly view storage schema | `database/migrations/2026_05_26_000003_rebuild_vehicle_views_hourly_buckets.php` |
| Vue widget | `resources/js/components/TrendingVehicles.vue` |
| Fetch / 30s polling | `resources/js/composables/useTrendingVehicles.js` |
| Feature tests | `tests/Feature/VehicleApiTest.php` |
| Architecture notes | [NOTES.md](NOTES.md) |

---

## Git commit history (high level)

Commits were kept small on purpose:

1. Vehicle detail API  
2. Hourly view-count schema  
3. Buffered view counter (cache)  
4. Trending API  
5. Vue trending widget  
6. Feature tests  
7. Documentation (`NOTES.md`, this README)

---

## DDEV (alternative setup)

If you use [DDEV](https://ddev.com/):

```bash
ddev start
cp .env.example .env
ddev composer install
ddev artisan key:generate
touch database/database.sqlite
ddev artisan migrate --seed
ddev npm install
ddev npm run dev
```

Open https://hiring-test.ddev.site

---

## Contact

**Arsen Apresyan** — arsen.apresyan.96@gmail.com  

Happy to walk through the solution or hand off to a technical reviewer on your side.
