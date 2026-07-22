<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\Skill;
use App\Models\Experience;
use App\Models\Education;
use App\Models\Certification;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        $profile = Profile::first() ?? new Profile;

        return view('admin.pages.about-me', compact('profile'));
    }

    public function update(Request $request)
    {
        $profile = Profile::first() ?? new Profile;
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'badge_text' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:50',
            'location' => 'nullable|string|max:255',
            'education_name' => 'nullable|string|max:255',
            'stat_projects' => 'nullable|integer|min:0',
            'stat_clients' => 'nullable|integer|min:0',
            'stat_years' => 'nullable|integer|min:0',
            'stat_technologies' => 'nullable|integer|min:0',
            'typing_phrases' => 'nullable|array',
            'social_platforms' => 'nullable|array',
            'social_platforms.*' => 'nullable|string|max:50',
            'social_urls' => 'nullable|array',
            'social_urls.*' => 'nullable|url|max:500',
            'contact_email_2' => 'nullable|email|max:255',
            'whatsapp' => 'nullable|string|max:50',
            'availability_hours' => 'nullable|string|max:255',
            'office_address' => 'nullable|string',
            'work_modes' => 'nullable|string|max:255',
        ]);
        $profile->fill(collect($validated)->except([
            'social_platforms', 'social_urls',
            'contact_email_2', 'whatsapp', 'availability_hours', 'office_address', 'work_modes'
        ])->toArray());
        $social_links = [];
        if ($request->has('social_platforms') && $request->has('social_urls')) {
            $platforms = $request->input('social_platforms');
            $urls = $request->input('social_urls');
            foreach ($platforms as $index => $platform) {
                if (!empty($platform) && !empty($urls[$index])) {
                    $social_links[$platform] = $urls[$index];
                }
            }
        }
        $profile->social_links = $social_links;
        $profile->contact_info = [
            'email_2' => $validated['contact_email_2'] ?? null,
            'whatsapp' => $validated['whatsapp'] ?? null,
            'availability_hours' => $validated['availability_hours'] ?? null,
            'office_address' => $validated['office_address'] ?? null,
            'work_modes' => $validated['work_modes'] ?? null,
        ];

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('profile_pic', 's3');
            if (!$path) {
                throw new \Exception("Failed to upload image to S3. Check if the file is valid and S3 permissions are correct.");
            }
            $profile->image_url = \Storage::disk('s3')->url($path);
        }

        $profile->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    public function publicAbout()
    {
        $profile = Profile::first() ?? new Profile;
        $skills = Skill::active()->ordered()->get();
        $experiences = Experience::active()->ordered()->get();
        $educations = Education::active()->ordered()->get();
        $certifications = Certification::active()->ordered()->get();
        $webContent = \App\Models\WebContent::first() ?? new \App\Models\WebContent;

        return view('about', compact('profile', 'skills', 'experiences', 'educations', 'certifications', 'webContent'));
    }
}
