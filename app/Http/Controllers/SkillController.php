<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function index()
    {
        $skills = Skill::ordered()->paginate(10);
        $grouped = $skills->groupBy('category')->map(fn ($cat) => $cat->groupBy(fn ($s) => $s->sub_category ?? ''));
        $groupCount = $grouped->flatten(1)->count();
        return view('admin.pages.skills', compact('skills', 'groupCount'));
    }

    public function publicIndex()
    {
        $skills = Skill::active()->ordered()->get();
        return view('skills', compact('skills'));
    }

    public function store(Request $request)
    {
        $skill = Skill::create($request->validate([
            'category' => 'required|string|max:100',
            'sub_category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'name' => 'required|string|max:255',
            'percentage' => 'nullable|integer|min:0|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]));

        ActivityLog::recordFromRequest(
            'Admin',
            'Added skill',
            "{$skill->name} ({$skill->category})"
        );

        return back()->with('success', 'Skill added.');
    }

    public function storeBulk(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:100',
            'sub_category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'skills' => 'nullable|string',
        ]);
        $names = array_values(array_filter(array_map('trim', explode(',', $validated['skills'] ?? ''))));
        if (empty($names)) {
            return back()->with('error', 'Please add at least one skill.');
        }
        $count = 0;
        foreach ($names as $i => $name) {
            $exists = Skill::where('category', $validated['category'])
                ->where('sub_category', $validated['sub_category'] ?? null)
                ->where('name', $name)->exists();
            if ($name && !$exists) {
                Skill::create([
                    'category' => $validated['category'],
                    'sub_category' => $validated['sub_category'] ?: null,
                    'description' => $validated['description'] ?: null,
                    'name' => $name,
                    'percentage' => 0,
                    'sort_order' => $i,
                ]);
                $count++;
            }
        }
        return back()->with('success', $count ? "{$count} skill(s) added." : 'No new skills added (duplicates skipped).');
    }

    public function update(Request $request, Skill $skill)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:100',
            'sub_category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'name' => 'nullable|string|max:255',
            'skills' => 'nullable|string',
            'percentage' => 'nullable|integer|min:0|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $names = array_values(array_filter(array_map('trim', explode(',', $validated['skills'] ?? $validated['name'] ?? ''))));
        if (empty($names)) {
            return back()->with('error', 'Please add at least one skill/tool.');
        }

        // Delete all skills in this group (same category + sub_category)
        Skill::where('category', $skill->category)
            ->where('sub_category', $skill->sub_category)
            ->delete();

        // Create new skills from the form
        foreach ($names as $i => $name) {
            Skill::create([
                'category' => $validated['category'],
                'sub_category' => $validated['sub_category'] ?: null,
                'description' => $validated['description'] ?: null,
                'name' => $name,
                'percentage' => $validated['percentage'],
                'sort_order' => $validated['sort_order'] ?? $i,
            ]);
        }

        ActivityLog::recordFromRequest(
            'Admin',
            'Updated skill group',
            $validated['category'] . ($validated['sub_category'] ? ' - '.$validated['sub_category'] : '')
        );

        return back()->with('success', count($names) . ' skill(s) updated.');
    }

    public function destroy(Skill $skill)
    {
        $count = Skill::where('category', $skill->category)
            ->where('sub_category', $skill->sub_category)
            ->delete();

        ActivityLog::recordFromRequest(
            'Admin',
            'Deleted skill group',
            $skill->category . ($skill->sub_category ? ' - '.$skill->sub_category : '')
        );
        return back()->with('success', $count . ' skill(s) deleted.');
    }
}
