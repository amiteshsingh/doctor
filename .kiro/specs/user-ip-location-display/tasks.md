# Implementation Plan: user-ip-location-display

## Overview

IP-based location display feature ke liye implementation tasks. `users` table mein location columns add karne se lekar admin panel mein display karne tak — 6 tasks hain jo sequential aur parallel dono mein execute honge.

## Tasks

- [x] 1. Database Migration — IP Location Columns Add karna
  - CREATE `database/migrations/2026_09_25_000001_add_ip_location_to_users_table.php`
  - `up()`: `ip_city` (string 100), `ip_region` (string 100), `ip_country` (string 100), `ip_isp` (string 150), `ip_lat` (decimal 10,7), `ip_lng` (decimal 10,7) — sab `->nullable()->after('ip_address')`
  - `down()`: sab 6 columns drop kare
  - Run `php artisan migrate`

- [x] 2. User Model Update — Fillable aur Casts
  - MODIFY `app/Models/User.php`
  - `$fillable` mein add: `ip_city`, `ip_region`, `ip_country`, `ip_isp`, `ip_lat`, `ip_lng`
  - `casts()` mein add: `ip_lat => float`, `ip_lng => float`
  - Depends on: Task 1

- [x] 3. IpLocationService — Create Service Class
  - CREATE `app/Services/IpLocationService.php`
  - Namespace: `App\Services`, method: `public function fetch(string $ip): ?array`
  - Private IP ranges check (`127.`, `10.`, `192.168.`, `::1`) → return `null`
  - `Http::timeout(5)->get("http://ip-api.com/json/{$ip}")` — try/catch mein wrap
  - `$data['status'] !== 'success'` → return `null`
  - Lat (-90..90) aur Lng (-180..180) validate karo — invalid ho to `null`
  - Return: `['city', 'region', 'country', 'isp', 'lat', 'lng']`

- [x] 4. UserController — Login aur Register mein Location Save karo
  - MODIFY `app/Http/Controllers/Api/UserController.php`
  - `login()`: `$user->save()` ke baad IP fetch karo, location merge karo, `$user->update(...)` — try/catch mein, login block na ho
  - `register()`: `User::create()` ke baad same pattern — `$user->update(...)` with location
  - Private IP (127.0.0.1) ho to sirf `ip_address` save ho, location skip
  - Depends on: Task 2, Task 3

- [x] 5. Admin View — Location Column Add karo
  - MODIFY `resources/views/admin/app_users/index.blade.php`
  - `<thead>` mein "Action" se pehle `<th>Location</th>` add karo
  - Empty state `colspan="9"` → `colspan="10"`
  - Row mein Action `<td>` se pehle location `<td>` add karo — city/region/country (bold), ISP (muted small), Maps link (📍) jab lat/lng available ho, warna "—"
  - Depends on: Task 2

- [x] 6. Artisan Command — BackfillIpLocations
  - CREATE `app/Console/Commands/BackfillIpLocations.php`
  - Signature: `users:backfill-ip-locations {--limit= : Max users to process}`
  - Query: `User::whereNotNull('ip_address')->whereNull('ip_city')`
  - `--limit` option se `->take(n)` apply karo
  - Private IP → skip with message; null result → fail message; success → update + info message
  - Har request ke baad `sleep(1); usleep(500000);` (1.5s delay for rate limit)
  - End mein summary: Total processed, Success, Skipped
  - Depends on: Task 2, Task 3

## Task Dependency Graph

```json
{
  "waves": [
    { "wave": 1, "tasks": [1, 3] },
    { "wave": 2, "tasks": [2] },
    { "wave": 3, "tasks": [4, 5, 6] }
  ]
}
```

## Notes

- ip-api.com free tier: 45 req/min, no API key needed
- Local XAMPP development mein IP 127.0.0.1 hoga — private range check se automatically skip hoga
- Location fetch failure login/register ko kabhi block nahi karega
- Backfill command mein 1.5s delay rate limit ke liye zaroori hai
