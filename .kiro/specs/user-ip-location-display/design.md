# Design Document — user-ip-location-display

## Overview

Admin panel ke `/admin/app-users` page par IP-based location display karna. `users` table mein already `ip_address` save ho raha hai. Yeh feature ip-api.com (free, no key) se location fetch karke DB mein store karega aur admin listing mein show karega.

---

## Architecture

```
Mobile App Login/Register
        │
        ▼
Api\UserController (login / register)
        │
        ├── IpLocationService::fetch($ip)
        │         │
        │         └── ip-api.com/json/{ip}  (HTTP GET, timeout 5s)
        │
        └── User::update(ip_address, ip_city, ip_region, ip_country, ip_isp, ip_lat, ip_lng)

Admin Panel /admin/app-users
        │
        ▼
Admin\AppUsersController::index()
        │
        └── View: admin/app_users/index.blade.php
                  └── Location column (city, region, country + ISP + Maps link)

php artisan users:backfill-ip-locations
        │
        └── BackfillIpLocations command
                  └── IpLocationService::fetch($ip) per user (1.5s delay)
```

---

## Database Changes

### Migration: `add_ip_location_to_users_table`

`users` table mein 6 nullable columns add honge:

| Column      | Type              | Nullable | Notes                        |
|-------------|-------------------|----------|------------------------------|
| `ip_city`   | string(100)       | YES      | City name from ip-api.com    |
| `ip_region` | string(100)       | YES      | State/region name            |
| `ip_country`| string(100)       | YES      | Country name                 |
| `ip_isp`    | string(150)       | YES      | ISP/carrier name             |
| `ip_lat`    | decimal(10,7)     | YES      | Latitude (-90 to 90)         |
| `ip_lng`    | decimal(10,7)     | YES      | Longitude (-180 to 180)      |

All columns are `->nullable()->after('ip_address')`.

---

## Components

### 1. `App\Services\IpLocationService`

**File:** `app/Services/IpLocationService.php`

```php
class IpLocationService
{
    // Private ranges — skip these
    private array $privateRanges = ['127.', '10.', '192.168.', '::1', 'localhost'];

    public function fetch(string $ip): ?array
    // Returns: ['city', 'region', 'country', 'isp', 'lat', 'lng'] or null
    // Uses: Http::timeout(5)->get("http://ip-api.com/json/{$ip}")
    // Returns null on: private IP, API fail, network error, invalid lat/lng
}
```

**Logic flow:**
1. Check if IP is private → return `null`
2. `Http::timeout(5)->get("http://ip-api.com/json/{$ip}")`
3. If request fails (exception) → catch, return `null`
4. If `$data['status'] !== 'success'` → return `null`
5. Validate lat (-90..90) and lng (-180..180) → if invalid, set to null
6. Return array with keys: `city`, `region`, `country`, `isp`, `lat`, `lng`

---

### 2. Migration File

**File:** `database/migrations/2026_09_25_000001_add_ip_location_to_users_table.php`

Standard Laravel migration — `up()` adds 6 nullable columns after `ip_address`, `down()` drops them.

---

### 3. `App\Models\User` — Changes

Add to `$fillable`:
```php
'ip_city', 'ip_region', 'ip_country', 'ip_isp', 'ip_lat', 'ip_lng'
```

Add to `casts()`:
```php
'ip_lat' => 'float',
'ip_lng' => 'float',
```

---

### 4. `App\Http\Controllers\Api\UserController` — Changes

**`login()` method** — after `$user->save()` on line ~89:
```php
$ip = $request->ip();
$location = app(\App\Services\IpLocationService::class)->fetch($ip);
$updateData = ['ip_address' => $ip];
if ($location) {
    $updateData = array_merge($updateData, [
        'ip_city'    => $location['city'],
        'ip_region'  => $location['region'],
        'ip_country' => $location['country'],
        'ip_isp'     => $location['isp'],
        'ip_lat'     => $location['lat'],
        'ip_lng'     => $location['lng'],
    ]);
}
$user->update($updateData);
```

**`register()` method** — after `User::create()`:
```php
// Same pattern as login — fetch location and update if not null
```

Location failure kabhi bhi login/register response ko block nahi karega — try/catch ya null-check ke through.

---

### 5. Admin View — `resources/views/admin/app_users/index.blade.php`

**Table header** mein "Action" se pehle "Location" `<th>` add:
```html
<th>Location</th>
<th>Action</th>
```

**Empty state colspan** 9 → 10.

**Table row** mein "Action" `<td>` se pehle location `<td>` add:
```html
<td>
    @if($u->ip_city)
        <div style="font-weight:600;color:#1e40af;font-size:12px;">
            {{ $u->ip_city }}, {{ $u->ip_region }}, {{ $u->ip_country }}
        </div>
        @if($u->ip_isp)
        <div style="color:#94a3b8;font-size:11px;">{{ $u->ip_isp }}</div>
        @endif
        @if($u->ip_lat && $u->ip_lng)
        <a href="https://maps.google.com/?q={{ $u->ip_lat }},{{ $u->ip_lng }}"
           target="_blank" style="font-size:11px;color:#0a6ebd;">
            📍 Map
        </a>
        @endif
    @else
        <span style="color:#94a3b8;">—</span>
    @endif
</td>
```

---

### 6. `App\Console\Commands\BackfillIpLocations`

**File:** `app/Console/Commands/BackfillIpLocations.php`

**Signature:** `users:backfill-ip-locations {--limit= : Max users to process}`

**Logic:**
```
1. Query: users WHERE ip_address IS NOT NULL AND ip_city IS NULL
2. If --limit option given, apply ->take($limit)
3. foreach user:
   a. If private IP → $this->line("User #{id} skipped (private IP)") → $skipped++
   b. IpLocationService::fetch($ip)
   c. If null → $this->line("User #{id} failed") → continue
   d. Update user with location data → $this->line("User #{id} OK: city, country")
   e. sleep(1) + usleep(500000) = 1.5 second delay
4. Summary: "Done. Processed: X, Success: Y, Skipped: Z"
```

**Register in:** `app/Console/Commands/` — Laravel auto-discovers commands.

---

## Error Handling Strategy

| Scenario | Behavior |
|---|---|
| ip-api.com down | `Http::timeout(5)` throws, caught, return `null` — login proceeds normally |
| Private IP (127.0.0.1 on XAMPP) | Skip fetch, only save `ip_address` |
| API returns `status: fail` | Return `null` |
| Invalid lat/lng values | Set to `null` before storing |
| DB update fails | Wrapped in try/catch — login still succeeds |

---

## File Changelist

| File | Action |
|---|---|
| `database/migrations/2026_09_25_000001_add_ip_location_to_users_table.php` | CREATE |
| `app/Services/IpLocationService.php` | CREATE |
| `app/Models/User.php` | MODIFY — `$fillable` + `casts()` |
| `app/Http/Controllers/Api/UserController.php` | MODIFY — `login()` + `register()` |
| `resources/views/admin/app_users/index.blade.php` | MODIFY — Location column |
| `app/Console/Commands/BackfillIpLocations.php` | CREATE |
