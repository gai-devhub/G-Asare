<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Profile;
use App\Services\GitHubService;
use Illuminate\Support\Str;

class WelcomeController extends Controller
{
    public function __invoke(GitHubService $githubService)
    {
        $latestPosts = $this->getLatestBlogPosts(3);
        $webContent = \App\Models\WebContent::first() ?? new \App\Models\WebContent;
        $githubStats = $githubService->getStats();
        
        $galleryFolders = \App\Models\GalleryFolder::where('is_active', true)
            ->with(['items' => function($query) {
                $query->where('is_active', true)->orderBy('sort_order')->orderBy('created_at', 'desc');
            }])->get();

        return view('welcome', [
            'latestPosts' => $latestPosts, 
            'webContent' => $webContent,
            'galleryFolders' => $galleryFolders,
            'githubStats' => $githubStats
        ]);
    }

    /**
     * Map BlogPost models to the format expected by blog/article card views.
     */
    private function getLatestBlogPosts(int $limit = 3): array
    {
        $dbPosts = BlogPost::active()->published()->ordered()->limit($limit)->get();
        $profile = Profile::first();

        return $dbPosts->map(function ($post) use ($profile) {
            return [
                'slug' => $post->slug,
                'title' => $post->title,
                'category' => $post->category ?? 'Development',
                'excerpt' => $post->excerpt ?? Str::limit(strip_tags($post->content), 150),
                'image' => $post->image_path ? (Str::startsWith($post->image_path, 'http') ? $post->image_path : asset($post->image_path)) : asset('images/gilly.jpeg'),
                'author' => $post->author_name ?? $profile?->name ?? 'Admin',
                'author_avatar' => $post->author_image_path ? (Str::startsWith($post->author_image_path, 'http') ? $post->author_image_path : asset($post->author_image_path)) : ($profile?->image_url ? (Str::startsWith($profile->image_url, 'http') ? $profile->image_url : asset($profile->image_url)) : asset('images/afia.jpg')),
                'date' => $post->published_at?->format('F Y') ?? 'Draft',
            ];
        })->toArray();
    }
}
