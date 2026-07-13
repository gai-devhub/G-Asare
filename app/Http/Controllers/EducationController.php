<?php

namespace App\Http\Controllers;

use App\Models\Education;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    public function index()
    {
        $education = Education::ordered()->paginate(10);
        return view('admin.pages.education', compact('education'));
    }

    public function publicIndex()
    {
        $educations = Education::active()->ordered()->get();
        $certifications = \App\Models\Certification::active()->ordered()->get();
        $awards = \App\Models\Award::active()->ordered()->get();
        $documents = \App\Models\Document::active()->ordered()->get();

        return view('edu&certs', compact('educations', 'certifications', 'awards', 'documents'));
    }

    public function store(Request $request)
    {
        Education::create($request->validate([
            'degree' => 'required|string|max:255',
            'institution' => 'required|string|max:255',
            'date_from' => 'required|string|max:50',
            'date_to' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]));

        return back()->with('success', 'Education entry added.');
    }

    public function update(Request $request, Education $education)
    {
        $education->update($request->validate([
            'degree' => 'required|string|max:255',
            'institution' => 'required|string|max:255',
            'date_from' => 'required|string|max:50',
            'date_to' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]));

        return back()->with('success', 'Education entry updated.');
    }

    public function destroy(Education $education)
    {
        $education->delete();
        return back()->with('success', 'Education entry deleted.');
    }
}
