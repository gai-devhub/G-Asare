<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ActivityLog;
use App\Services\GitHubService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::ordered()->paginate(10);
        return view('admin.pages.projects', compact('projects'));
    }

    public function publicIndex(GitHubService $githubService)
    {
        $projects = Project::where('is_active', true)->ordered()->get();
        $featuredProject = Project::where('is_active', true)->where('is_featured', true)->first();
        if (!$featuredProject && $projects->count() > 0) {
            $featuredProject = $projects->first();
        }
        
        $githubStats = $githubService->getStats();
        
        return view('project', compact('projects', 'featuredProject', 'githubStats'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:projects,slug',
            'description' => 'nullable|string',
            'image_url' => 'nullable|image|max:10240',
            'category' => 'required|string|max:100',
            'tags' => 'nullable|string',
            'project_url' => 'nullable|url|max:500',
            'github_url' => 'nullable|url|max:500',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
        ]);
        $data['slug'] = $data['slug'] ?? Str::slug($data['title']);
        $data['tags'] = !empty($data['tags']) ? array_values(array_filter(array_map('trim', explode(',', $data['tags'])))) : null;
        $data['is_featured'] = $request->has('is_featured');

        if ($request->hasFile('image_url')) {
            $path = $request->file('image_url')->store('projects', 's3');
            $data['image_url'] = \Storage::disk()->url($path);
        }

        $project = Project::create($data);

        ActivityLog::recordFromRequest(
            'Admin',
            'Added new project',
            $project->title
        );
        return back()->with('success', 'Project added.');
    }

    public function update(Request $request, Project $project)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:projects,slug,' . $project->id,
            'description' => 'nullable|string',
            'image_url' => 'nullable|image|max:10240',
            'category' => 'required|string|max:100',
            'tags' => 'nullable|string',
            'project_url' => 'nullable|url|max:500',
            'github_url' => 'nullable|url|max:500',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
        ]);
        $data['slug'] = $data['slug'] ?? Str::slug($data['title']);
        $data['tags'] = !empty($data['tags']) ? array_values(array_filter(array_map('trim', explode(',', $data['tags'])))) : null;
        $data['is_featured'] = $request->has('is_featured');

        if ($request->hasFile('image_url')) {
            $path = $request->file('image_url')->store('projects', 's3');
            $data['image_url'] = \Storage::disk()->url($path);
        } else {
            unset($data['image_url']); // Don't overwrite existing image if no new file is uploaded
        }

        $project->update($data);

        ActivityLog::recordFromRequest(
            'Admin',
            'Updated project',
            $project->title
        );
        return back()->with('success', 'Project updated.');
    }

    public function destroy(Project $project)
    {
        $title = $project->title;
        $project->delete();
        ActivityLog::recordFromRequest(
            'Admin',
            'Deleted project',
            $title
        );
        return back()->with('success', 'Project deleted.');
    }
}
