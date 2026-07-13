<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    public function index()
    {
        $experiences = Experience::ordered()->paginate(10);
        return view('admin.pages.journey', compact('experiences'));
    }

    public function publicIndex()
    {
        $experiences = Experience::active()->ordered()->get();
        return view('journey', compact('experiences'));
    }

    public function store(Request $request)
    {
        Experience::create($request->validate([
            'role' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'date_from' => 'required|string|max:50',
            'date_to' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]));

        return back()->with('success', 'Experience added.');
    }

    public function update(Request $request, Experience $experience)
    {
        $experience->update($request->validate([
            'role' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'date_from' => 'required|string|max:50',
            'date_to' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]));

        return back()->with('success', 'Experience updated.');
    }

    public function destroy(Experience $experience)
    {
        $experience->delete();
        return back()->with('success', 'Experience deleted.');
    }
}
