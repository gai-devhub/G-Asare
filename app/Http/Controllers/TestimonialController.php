<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::ordered()->get();
        return view('admin.pages.testimonials', compact('testimonials'));
    }

    public function store(Request $request)
    {
        Testimonial::create($request->validate([
            'quote' => 'required|string',
            'author_name' => 'required|string|max:255',
            'author_role' => 'nullable|string|max:255',
            'author_image_url' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]));

        return back()->with('success', 'Testimonial added.');
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $testimonial->update($request->validate([
            'quote' => 'required|string',
            'author_name' => 'required|string|max:255',
            'author_role' => 'nullable|string|max:255',
            'author_image_url' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]));

        return back()->with('success', 'Testimonial updated.');
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        return back()->with('success', 'Testimonial deleted.');
    }
}
