<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::latest()->paginate(20);
        return view('admin.blog.index', compact('blogs'));
    }

    public function add()
    {
        return view('admin.blog.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'category' => 'required',
            'excerpt' => 'required',
            'content' => 'required',
            'image' => 'nullable|image|max:2048'
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title);
        $data['added_by'] = auth()->id();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('uploads/blog'), $imageName);
            $data['image'] = $imageName;
        }

        Blog::create($data);

        return redirect()->route('admin.blog.index')->with('success', 'Blog created successfully');
    }

    public function edit($id)
    {
        $blog = Blog::findOrFail($id);
        return view('admin.blog.edit', compact('blog'));
    }

    public function update(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);

        $request->validate([
            'title' => 'required|max:255',
            'category' => 'required',
            'excerpt' => 'required',
            'content' => 'required',
            'image' => 'nullable|image|max:2048'
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title);
        $data['updated_by'] = auth()->id();

        if ($request->hasFile('image')) {
            if ($blog->image && file_exists(public_path('uploads/blog/' . $blog->image))) {
                unlink(public_path('uploads/blog/' . $blog->image));
            }
            $image = $request->file('image');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('uploads/blog'), $imageName);
            $data['image'] = $imageName;
        }

        $blog->update($data);

        return redirect()->route('admin.blog.index')->with('success', 'Blog updated successfully');
    }

    public function delete($id)
    {
        $blog = Blog::findOrFail($id);
        
        if ($blog->image && file_exists(public_path('uploads/blog/' . $blog->image))) {
            unlink(public_path('uploads/blog/' . $blog->image));
        }
        
        $blog->delete();

        return redirect()->route('admin.blog.index')->with('success', 'Blog deleted successfully');
    }
}
