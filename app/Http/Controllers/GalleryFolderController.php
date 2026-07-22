<?php

namespace App\Http\Controllers;

use App\Models\GalleryFolder;
use Illuminate\Http\Request;

class GalleryFolderController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('gallery_folders');
            $validated['cover_image_url'] = '/storage/' . $path;
        }

        GalleryFolder::create($validated);

        return back()->with('success', 'Folder created.');
    }

    public function show(GalleryFolder $galleryFolder)
    {
        $items = $galleryFolder->items()->orderBy('sort_order')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.pages.gallery-folder', compact('galleryFolder', 'items'));
    }

    public function update(Request $request, GalleryFolder $galleryFolder)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('gallery_folders');
            $validated['cover_image_url'] = '/storage/' . $path;
        }

        $galleryFolder->update($validated);

        return back()->with('success', 'Folder updated.');
    }

    public function destroy(GalleryFolder $galleryFolder)
    {
        $galleryFolder->items()->delete(); // Delete all items inside
        $galleryFolder->delete();
        return redirect()->route('admin.gallery')->with('success', 'Folder and its contents deleted.');
    }

    public function publicShow(GalleryFolder $galleryFolder)
    {
        if (!$galleryFolder->is_active) {
            abort(404);
        }
        $items = $galleryFolder->items()->where('is_active', true)->orderBy('sort_order')->orderBy('created_at', 'desc')->get();
        return view('gallery-show', compact('galleryFolder', 'items'));
    }
}
