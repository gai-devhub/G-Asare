<?php

namespace App\Http\Controllers;

use App\Models\GalleryItem;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        $folders = \App\Models\GalleryFolder::orderBy('created_at', 'desc')->paginate(12);
        $categories = \App\Models\GalleryFolder::whereNotNull('category')->pluck('category')->unique()->sort()->values();
        return view('admin.pages.gallery', compact('folders', 'categories'));
    }

    public function publicIndex()
    {
        $folders = \App\Models\GalleryFolder::where('is_active', true)->orderBy('created_at', 'desc')->get();
        return view('gallery', compact('folders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'images' => 'required|array',
            'images.*' => 'image|max:5120', // 5MB max per image
            'category' => 'nullable|string|max:100',
            'gallery_folder_id' => 'nullable|exists:gallery_folders,id',
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('gallery');
                
                GalleryItem::create([
                    'image_url' => '/storage/' . $path,
                    'category' => $validated['category'] ?? null,
                    'gallery_folder_id' => $validated['gallery_folder_id'] ?? null,
                    'is_active' => true,
                    'sort_order' => 0,
                ]);
            }
        }

        return back()->with('success', count($request->file('images')) . ' gallery items added.');
    }

    public function update(Request $request, GalleryItem $galleryItem)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:5120',
            'category' => 'nullable|string|max:100',
            'gallery_folder_id' => 'nullable|exists:gallery_folders,id',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('gallery');
            $validated['image_url'] = '/storage/' . $path;
        }

        $galleryItem->update($validated);

        return back()->with('success', 'Gallery item updated.');
    }

    public function destroy(GalleryItem $galleryItem)
    {
        $galleryItem->delete();
        return back()->with('success', 'Gallery item deleted.');
    }
}
