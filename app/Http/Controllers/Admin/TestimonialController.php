<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::orderByDesc('id')->paginate(10);
        return view('admin.testimonial.index', compact('testimonials'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'message'     => 'required|string',
            'rating'      => 'required|integer|min:1|max:5',
            'photo'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['name', 'designation', 'message', 'rating']);
        $data['status'] = 1;

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('upload/testimonials', $filename, 'public');
            $data['photo'] = $filename;
        }

        Testimonial::create($data);

        return redirect()->route('admin.testimonial.index')->with('success', 'Testimonial added successfully.');
    }

    public function edit($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        return view('admin.testimonial.edit', compact('testimonial'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'message'     => 'required|string',
            'rating'      => 'required|integer|min:1|max:5',
            'photo'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $testimonial = Testimonial::findOrFail($id);
        $data = $request->only(['name', 'designation', 'message', 'rating', 'status']);

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('upload/testimonials', $filename, 'public');
            $data['photo'] = $filename;
        }

        $testimonial->update($data);

        return redirect()->route('admin.testimonial.index')->with('success', 'Testimonial updated successfully.');
    }

    public function toggle($id)
    {
        $t = Testimonial::findOrFail($id);
        $t->update(['status' => !$t->status]);
        return redirect()->route('admin.testimonial.index')->with('success', 'Status updated.');
    }

    public function destroy($id)
    {
        Testimonial::findOrFail($id)->delete();
        return redirect()->route('admin.testimonial.index')->with('success', 'Testimonial deleted.');
    }
}
