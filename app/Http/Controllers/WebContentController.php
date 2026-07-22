<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WebContent;

class WebContentController extends Controller
{
    public function edit()
    {
        $webContent = WebContent::first() ?? new WebContent;
        return view('admin.pages.web', compact('webContent'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'hero_image_url' => 'nullable|image|max:10240',

            'story_title' => 'nullable|string|max:255',
            'story_subtitle' => 'nullable|string|max:255',
            'story_content' => 'nullable|string',
            'philosophy_title' => 'nullable|string|max:255',
            'philosophy_subtitle' => 'nullable|string|max:255',
            'philosophy_content' => 'nullable|string',
        ]);

        $webContent = WebContent::first() ?? new WebContent;
        $webContent->fill(collect($validated)->except(['hero_image_url'])->toArray());

        if ($request->hasFile('hero_image_url')) {
            $path = $request->file('hero_image_url')->store('web_content');
            $webContent->hero_image_url = 'storage/' . $path;
        }



        $webContent->save();

        return back()->with('success', 'Web Content updated successfully.');
    }
}
