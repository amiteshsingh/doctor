# Requirements Document

## Introduction

Yeh feature admin panel ke `/admin/app-users` page par users ki location information display karna hai. `users` table mein already `ip_address` save ho raha hai. Is feature mein ip-api.com (free, no key needed) se IP ke basis par city, region, country, ISP, latitude, aur longitude fetch karke `users` table mein store kiya jayega, aur admin listing page par **Location** column mein show kiya jayega. Existing users ke liye bhi location backfill karne ki capability hogi via Artisan command. Google Maps link bhi show hoga jab lat/lng available ho.

## Glossary

- **IpLocationService**: Laravel service class jo ip-api.com API ko call karke IP ka location data fetch karta hai
- **ip-api.com**: Free IP geolocation API (`http://ip-api.com/json/{ip}`) — no API key needed, 45 requests/minute rate limit
- **Location Data**: IP se derived information: `ip_city`, `ip_region`, `ip_country`, `ip_isp`, `ip_lat`, `ip_lng`
- **AppUsersController**: `App\Http\Controllers\Admin\AppUsersController` — admin panel mein app users manage karne ka controller
- **UserController**: `App\Http\Controllers\Api\UserController` — mobile app users ke login/register handle karne ka API controller
- **BackfillIpLocations**: Artisan console command jo existing users ke liye IP location data fetch karta hai
- **User**: `App\Models\User` — application ka main user model
- **Admin**: Admin panel access karne wala administrator

---

## Requirements

### Requirement 1: Users Table mein Location Columns Add karna

**User Story:** As an Admin, I want users ki IP-based location automatically database mein store ho, so that har baar API call karne ki zaroorat na ho aur location data persistent rahe.

#### Acceptance Criteria

1. THE System SHALL `users` table mein ye naye nullable columns add karna ke liye migration create kare: `ip_city` (string, nullable), `ip_region` (string, nullable), `ip_country` (string, nullable), `ip_isp` (string, nullable), `ip_lat` (decimal 10,7, nullable), `ip_lng` (decimal 10,7, nullable)
2. THE User model SHALL `ip_city`, `ip_region`, `ip_country`, `ip_isp`, `ip_lat`, `ip_lng` ko apni `$fillable` array mein include kare
3. WHEN migration run hoti hai existing data wale production database par, THE System SHALL existing rows ko affect kiye bina naye columns add kare (nullable columns)

---

### Requirement 2: IpLocationService — IP se Location Fetch karna

**User Story:** As a Developer, I want ek centralized service ho jo ip-api.com se location fetch kare, so that code duplication na ho aur rate limiting ek jagah handle ho sake.

#### Acceptance Criteria

1. THE IpLocationService SHALL `http://ip-api.com/json/{ip}` endpoint ko call karke location data fetch kare
2. WHEN ip-api.com se successful response milta hai (status = "success"), THE IpLocationService SHALL `city`, `regionName`, `country`, `isp`, `lat`, `lon` fields extract karke return kare
3. IF ip-api.com API call fail ho (network error, timeout, HTTP error), THEN THE IpLocationService SHALL exception throw karne ki jagah `null` return kare
4. IF provided IP address private/reserved range ka ho (127.x.x.x, 192.168.x.x, 10.x.x.x, localhost), THEN THE IpLocationService SHALL API call kiye bina `null` return kare
5. IF ip-api.com response mein `status` field "fail" ho, THEN THE IpLocationService SHALL `null` return kare
6. THE IpLocationService SHALL HTTP request timeout 5 seconds set kare taaki slow API response se application hang na ho
7. THE IpLocationService SHALL Laravel HTTP client (Illuminate\Support\Facades\Http) use kare (Guzzle direct nahi)

---

### Requirement 3: Login par Automatically Location Fetch aur Store karna

**User Story:** As a Developer, I want user login ke time uski location automatically update ho, so that Admin ko latest location data milta rahe bina manual intervention ke.

#### Acceptance Criteria

1. WHEN user API login successfully complete hota hai (`UserController@login`), THE UserController SHALL `IpLocationService` use karke request IP se location fetch kare
2. WHEN location data successfully fetch ho jata hai, THE UserController SHALL user record mein `ip_address`, `ip_city`, `ip_region`, `ip_country`, `ip_isp`, `ip_lat`, `ip_lng` update kare
3. IF location fetch fail ho ya null return kare, THEN THE UserController SHALL sirf `ip_address` update kare aur login response successful return kare (location failure login block nahi karega)
4. WHEN user register karta hai (`UserController@register`), THE UserController SHALL `ip_address` save karne ke saath location data bhi fetch karke store kare
5. IF `$request->ip()` se milne wali IP private range ki ho (XAMPP local development mein 127.0.0.1), THEN THE UserController SHALL location fetch attempt skip kare

---

### Requirement 4: Admin Listing mein Location Column Display karna

**User Story:** As an Admin, I want app users ki listing mein Location column dikhe, so that main dekh sakun ki users kis city/region/country se hain.

#### Acceptance Criteria

1. THE AppUsersController SHALL admin users listing mein location data ke saath users fetch kare (existing paginated query mein koi change nahi — model attributes use honge)
2. THE Admin listing view SHALL existing table mein "Action" column se pehle "Location" column add kare
3. WHEN user ke `ip_city`, `ip_region`, `ip_country` columns mein data available ho, THE View SHALL "City, Region, Country" format mein display kare (e.g., "Mumbai, Maharashtra, India")
4. WHEN user ki location unknown ho (null columns), THE View SHALL "—" display kare
5. WHEN user ke `ip_lat` aur `ip_lng` dono available hon, THE View SHALL Google Maps link show kare (e.g., `https://maps.google.com/?q={lat},{lng}`) jo new tab mein open ho
6. THE View SHALL location column mein ISP information bhi show kare (small muted text mein, city/region/country ke neeche)
7. THE View SHALL table header colspan "Action" column se pehle "Location" header add kare aur `colspan` values update kare jahan zaroorat ho (empty state `td`)

---

### Requirement 5: Existing Users ke liye Location Backfill — Artisan Command

**User Story:** As an Admin, I want existing users (jinke paas ip_address hai lekin location nahi) ke liye bhi location data fill ho sake, so that purana data bhi useful ho.

#### Acceptance Criteria

1. THE BackfillIpLocations Artisan command SHALL `php artisan users:backfill-ip-locations` se run ho
2. WHEN command run hoti hai, THE BackfillIpLocations SHALL sirf unhe users process kare jinke paas `ip_address` non-null ho lekin `ip_city` null ho (already fetched users ko skip kare)
3. THE BackfillIpLocations SHALL ip-api.com ke rate limit (45 req/min) respect karne ke liye har request ke baad 1.5 seconds delay rakhe
4. THE BackfillIpLocations SHALL har processed user ke liye console par output show kare: user ID, IP, aur result (success/skip/fail)
5. WHEN command complete hoti hai, THE BackfillIpLocations SHALL total processed, success, aur skipped counts summary show kare
6. IF koi user ka IP private range ka ho, THEN THE BackfillIpLocations SHALL us user ko "skipped (private IP)" mark kare aur next par move kare
7. THE BackfillIpLocations SHALL `--limit` option support kare (e.g., `php artisan users:backfill-ip-locations --limit=100`) taaki batch mein run kiya ja sake

---

### Requirement 6: Location Data ki Integrity aur Privacy

**User Story:** As a Developer, I want location data safely store ho, so that koi data corruption ya security issue na ho.

#### Acceptance Criteria

1. THE System SHALL location columns mein sirf ip-api.com se milne wali values store kare — user-provided input se nahi
2. THE IpLocationService SHALL latitude aur longitude values store karne se pehle validate kare ki wo valid decimal ranges mein hain (lat: -90 to 90, lng: -180 to 180)
3. THE System SHALL ip-api.com API key ya credentials store/log nahi kare (API key-free hai, lekin koi accidental logging na ho)
4. WHERE admin search functionality exist karti hai users page par, THE AppUsersController SHALL location columns par search nahi kare (existing name/email/phone search hi rahega)
