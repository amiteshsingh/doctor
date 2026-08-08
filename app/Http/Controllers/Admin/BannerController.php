<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index() {
        $banners = Banner::orderBy('sort_order')->orderBy('id')->get();
        return view('admin.banner.index', compact('banners'));
    }

    public function store(Request $request) {
        $request->validate([
            'title'    => 'nullable|string|max:100',
            'subtitle' => 'nullable|string|max:200',
            'icon'     => 'nullable|string|max:10',
            'color'    => 'required|string',
            'image'    => 'nullable|image|max:2048',
        ]);

        $imageUrl = null;
        if ($request->hasFile('image')) {
            $imageName = time() . '_' . str_replace(' ', '_', $request->file('image')->getClientOriginalName());
            $uploadPath = base_path('uploads/banners');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            $request->file('image')->move($uploadPath, $imageName);
            $imageUrl = url('uploads/banners/' . $imageName);
        }

        Banner::create([
            'title'      => $request->title,
            'subtitle'   => $request->subtitle,
            'icon'       => $request->icon,
            'color'      => $request->color,
            'image'      => $imageUrl,
            'link'       => $request->link,
            'is_active'  => $request->has('is_active'),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return back()->with('success', 'Banner add ho gaya!');
    }

    public function toggle($id) {
        $banner = Banner::findOrFail($id);
        $banner->update(['is_active' => !$banner->is_active]);
        return back()->with('success', 'Banner status update ho gaya!');
    }

    public function delete($id) {
        $banner = Banner::findOrFail($id);
        if ($banner->image) {
            $filename = basename($banner->image);
            $filePath = base_path('uploads/banners/' . $filename);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        $banner->delete();
        return back()->with('success', 'Banner delete ho gaya!');
    }
}
