# Implementation Plan: home-banner-slider

## Overview

Website home page par hero section ke neeche admin-manageable image slider add karna. SwiperJS use hoga (CDN). Existing `banners` table mein button fields add honge. Admin form update hoga. PageController banners pass karega. Home page par slider render hoga.

## Tasks

- [ ] 1. Migration — banners table mein button_text aur button_url add karo
  - CREATE `database/migrations/2026_10_13_000001_add_button_fields_to_banners_table.php`
  - `up()`: `$table->string('button_text', 100)->nullable()->after('link');` aur `$table->string('button_url', 500)->nullable()->after('button_text');`
  - `down()`: dono columns drop karo

- [ ] 2. Banner Model — fillable update karo
  - MODIFY `app/Models/Banner.php`
  - `$fillable` mein add: `'button_text'`, `'button_url'`
  - Depends on: Task 1

- [ ] 3. Admin BannerController — store method mein button fields save karo
  - MODIFY `app/Http/Controllers/Admin/BannerController.php`
  - `store()` mein validation add karo: `'button_text' => 'nullable|string|max:100'`, `'button_url' => 'nullable|url|max:500'`
  - `Banner::create()` mein `'button_text' => $request->button_text`, `'button_url' => $request->button_url` add karo
  - Depends on: Task 2

- [ ] 4. Admin Banner View — form mein button fields add karo
  - MODIFY `resources/views/admin/banner/index.blade.php`
  - Form mein "Link" input ke neeche 2 new fields add karo:
    - Button Text: `<input type="text" name="button_text" placeholder="Jaise: Book Now, Learn More">`
    - Button URL: `<input type="text" name="button_url" placeholder="https://...">`
  - Banner list mein bhi button info show karo (agar set ho to small badge)
  - Depends on: Task 3

- [ ] 5. PageController — banners fetch karke view mein pass karo
  - MODIFY `app/Http/Controllers/PageController.php`
  - `index()` method mein add karo: `$banners = \App\Models\Banner::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get();`
  - `compact()` mein `'banners'` add karo
  - Depends on: Task 2

- [ ] 6. Home Page — SwiperJS slider add karo
  - MODIFY `resources/views/page/index.blade.php`
  - Hero section ke turant baad (stats/ticker section se pehle) slider section add karo
  - SwiperJS CDN include karo (CSS + JS)
  - `@if($banners->isNotEmpty())` check ke andar slider render karo
  - Har slide: background image (object-fit cover) ya color gradient fallback, overlay, title + subtitle text, optional button
  - Swiper config: autoplay 4000ms, loop, pagination dots, prev/next arrows
  - Responsive height: 400px desktop, 260px mobile
  - Depends on: Task 5

## Task Dependency Graph

```json
{
  "waves": [
    { "wave": 1, "tasks": [1] },
    { "wave": 2, "tasks": [2] },
    { "wave": 3, "tasks": [3, 5] },
    { "wave": 4, "tasks": [4, 6] }
  ]
}
```

## Notes

- SwiperJS v11 CDN use karo: `https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css` aur JS
- Mobile app API (`Api/BannerController`) ko touch mat karo — sirf web
- `banners` table mein `image` field already URL store karta hai (full URL)
- Agar image nahi hai to `background: banner.color` use karo as fallback
