<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;

class BannerController extends Controller
{
    public function index() {
        $banners = Banner::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn($b) => [
                'id'       => $b->id,
                'title'    => $b->title,
                'subtitle' => $b->subtitle,
                'icon'     => $b->icon,
                'color'    => $b->color,
                'image'    => $b->image ?: null,
                'link'     => $b->link,
            ]);

        return response()->json(['status' => 200, 'data' => $banners]);
    }
}
