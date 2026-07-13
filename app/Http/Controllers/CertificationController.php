<?php

namespace App\Http\Controllers;

use App\Models\Certification;
use Illuminate\Http\Request;

class CertificationController extends Controller
{
    public function index()
    {
        $certifications = Certification::ordered()->paginate(10);
        return view('admin.pages.certifications', compact('certifications'));
    }

    public function store(Request $request)
    {
        Certification::create($request->validate([
            'name' => 'required|string|max:255',
            'issuer' => 'required|string|max:255',
            'year' => 'nullable|string|max:20',
            'credential_url' => 'nullable|url|max:500',
            'icon' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]));

        return back()->with('success', 'Certification added.');
    }

    public function update(Request $request, Certification $certification)
    {
        $certification->update($request->validate([
            'name' => 'required|string|max:255',
            'issuer' => 'required|string|max:255',
            'year' => 'nullable|string|max:20',
            'credential_url' => 'nullable|url|max:500',
            'icon' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]));

        return back()->with('success', 'Certification updated.');
    }

    public function destroy(Certification $certification)
    {
        $certification->delete();
        return back()->with('success', 'Certification deleted.');
    }
}
