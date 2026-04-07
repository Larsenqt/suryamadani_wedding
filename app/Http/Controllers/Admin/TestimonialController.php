<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::latest()->paginate(10);
        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        return view('admin.testimonials.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $image = $request->file('image')->store('testimonials', 'public');

        Testimonial::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $image
        ]);

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimoni berhasil ditambahkan');
    }

    public function edit($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, $id)
    {
        $testimonial = Testimonial::findOrFail($id);

        $request->validate([
            'title' => 'required',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($testimonial->image);
            $image = $request->file('image')->store('testimonials', 'public');
        } else {
            $image = $testimonial->image;
        }

        $testimonial->update([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $image
        ]);

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimoni berhasil diupdate');
    }

    public function destroy($id)
    {
        $testimonial = Testimonial::findOrFail($id);

        Storage::disk('public')->delete($testimonial->image);
        $testimonial->delete();

        return back()->with('success', 'Testimoni berhasil dihapus');
    }
}