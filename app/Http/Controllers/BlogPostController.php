<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Profile;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogPostController extends Controller
{
    /** Admin: list all blog posts */
    public function index()
    {
        $posts = BlogPost::ordered()->paginate(10);
        return view('admin.pages.blog-posts', compact('posts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_posts,slug',
            'category' => 'nullable|string|max:100',
            'excerpt' => 'nullable|string',
            'content' => 'nullable|string',
            'image_path' => 'nullable|image|max:10240',
            'author_name' => 'nullable|string|max:255',
            'author_image_path' => 'nullable|image|max:10240',
            'signature' => 'nullable|string',
            'published_at' => 'nullable|date',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);
        $data['slug'] = $data['slug'] ?? Str::slug($data['title']);

        if ($request->hasFile('image_path')) {
            $path = $request->file('image_path')->store('blog_posts', 's3');
            $data['image_path'] = \Storage::disk('s3')->url($path);
        }

        if ($request->hasFile('author_image_path')) {
            $path = $request->file('author_image_path')->store('blog_posts', 's3');
            $data['author_image_path'] = \Storage::disk('s3')->url($path);
        }

        $post = BlogPost::create($data);

        ActivityLog::recordFromRequest(
            'Admin',
            'Added new blog post',
            $post->title
        );
        return back()->with('success', 'Blog post added.');
    }

    public function update(Request $request, BlogPost $blogPost)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_posts,slug,' . $blogPost->id,
            'excerpt' => 'nullable|string',
            'content' => 'nullable|string',
            'image_path' => 'nullable|image|max:10240',
            'author_name' => 'nullable|string|max:255',
            'author_image_path' => 'nullable|image|max:10240',
            'signature' => 'nullable|string',
            'published_at' => 'nullable|date',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);
        $data['slug'] = $data['slug'] ?? Str::slug($data['title']);

        if ($request->hasFile('image_path')) {
            $path = $request->file('image_path')->store('blog_posts', 's3');
            $data['image_path'] = \Storage::disk('s3')->url($path);
        } else {
            unset($data['image_path']);
        }

        if ($request->hasFile('author_image_path')) {
            $path = $request->file('author_image_path')->store('blog_posts', 's3');
            $data['author_image_path'] = \Storage::disk('s3')->url($path);
        } else {
            unset($data['author_image_path']);
        }

        $blogPost->update($data);

        ActivityLog::recordFromRequest(
            'Admin',
            'Updated blog post',
            $blogPost->title
        );
        return back()->with('success', 'Blog post updated.');
    }

    public function destroy(BlogPost $blogPost)
    {
        $title = $blogPost->title;
        $blogPost->delete();
        ActivityLog::recordFromRequest(
            'Admin',
            'Deleted blog post',
            $title
        );
        return back()->with('success', 'Blog post deleted.');
    }

    /** Public: blog list page */
    public function publicIndex()
    {
        $dbPosts = BlogPost::ordered()->get();
        $profile = Profile::first();

        $heroTitle = \App\Models\Setting::get('blog_hero_title', 'Web Development Insights, Trends and News');
        $heroDescription = \App\Models\Setting::get('blog_hero_description', 'Stay informed on the latest web technologies, framework updates, design trends, and development best practices.');
        $heroImage = \App\Models\Setting::get('blog_hero_image', 'images/gilly.jpeg');
        $heroImageUrl = $heroImage && !Str::startsWith($heroImage, 'http') ? asset($heroImage) : ($heroImage ?: asset('images/gilly.jpeg'));

        $posts = $dbPosts->map(function ($post) use ($profile) {
            return [
                'slug' => $post->slug,
                'title' => $post->title,
                'category' => $post->category ?? 'Development',
                'excerpt' => $post->excerpt ?? Str::limit(strip_tags($post->content), 150),
                'image' => $post->image_path ?? asset('images/gilly.jpeg'),
                'author' => $post->author_name ?? $profile?->name ?? 'Admin',
                'author_image' => $post->author_image_path ?? $profile?->image_path ?? asset('images/afia.jpg'),
                'date' => $post->published_at?->format('F Y') ?? 'Draft',
            ];
        })->toArray();

        $sidebarTitle = \App\Models\Setting::get('blog_sidebar_title', 'Driving Digital Solutions for a Stronger Online Future');
        $sidebarDescription = \App\Models\Setting::get('blog_sidebar_description', 'I help businesses build web experiences that convert visitors into customers and scale with growth.');

        return view('blog', [
            'posts' => $posts,
            'heroTitle' => $heroTitle,
            'heroDescription' => $heroDescription,
            'heroImageUrl' => $heroImageUrl,
            'sidebarTitle' => $sidebarTitle,
            'sidebarDescription' => $sidebarDescription,
        ]);
    }

    /** Public: single blog post */
    public function publicShow(string $slug)
    {
        $post = BlogPost::where('slug', $slug)->first();
        if (!$post) {
            abort(404);
        }
        $profile = Profile::first();

        $postData = [
            'slug' => $post->slug,
            'title' => $post->title,
            'category' => $post->category ?? 'Development',
            'excerpt' => $post->excerpt ?? '',
            'content' => $post->content ?? '',
            'image' => $post->image_path ?? asset('images/gilly.jpeg'),
            'author' => $post->author_name ?? $profile?->name ?? 'Admin',
            'author_image' => $post->author_image_path ?? $profile?->image_path ?? asset('images/afia.jpg'),
            'date' => $post->published_at?->format('F Y') ?? 'Draft',
            'signature' => $post->signature ?? '',
        ];

        return view('blog-post', ['post' => $postData]);
    }
}
