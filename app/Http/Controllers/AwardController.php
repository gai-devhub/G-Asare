<?php

namespace App\Http\Controllers;

use App\Models\Award;
use Illuminate\Http\Request;

class AwardController extends Controller
{
    public function index()
    {
        $awards = Award::ordered()->paginate(10);
        return view('admin.pages.awards', compact('awards'));
    }

    public function store(Request $request)
    {
        Award::create($request->validate([
            'icon' => 'nullable|string|max:50',
            'title' => 'required|string|max:255',
            'issuer' => 'nullable|string|max:255',
            'date' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'image_url' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]));

        return back()->with('success', 'Award added.');
    }

    public function update(Request $request, Award $award)
    {
        $award->update($request->validate([
            'icon' => 'nullable|string|max:50',
            'title' => 'required|string|max:255',
            'issuer' => 'nullable|string|max:255',
            'date' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'image_url' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]));

        return back()->with('success', 'Award updated.');
    }

    public function destroy(Award $award)
    {
        $award->delete();
        return back()->with('success', 'Award deleted.');
    }
}
