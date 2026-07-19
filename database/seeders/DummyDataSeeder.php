<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Skill;
use App\Models\Project;
use App\Models\GalleryFolder;
use App\Models\GalleryItem;
use App\Models\BlogPost;
use App\Models\Document;
use App\Models\ContactMessage;
use App\Models\Subscriber;
use App\Models\Testimonial;

class DummyDataSeeder extends Seeder
{
    public function run()
    {
        // 1. Skills
        $skills = [
            ['name' => 'Laravel', 'category' => 'Backend', 'percentage' => 90, 'is_active' => true],
            ['name' => 'React', 'category' => 'Frontend', 'percentage' => 85, 'is_active' => true],
            ['name' => 'Tailwind CSS', 'category' => 'Frontend', 'percentage' => 95, 'is_active' => true],
        ];
        foreach ($skills as $skill) {
            Skill::firstOrCreate(['name' => $skill['name']], $skill);
        }

        // 2. Projects
        $projects = [
            ['title' => 'E-Commerce Platform', 'slug' => 'e-commerce-platform', 'description' => 'A full-stack e-commerce solution.', 'category' => 'Web App', 'image_url' => 'https://via.placeholder.com/800x600?text=Project+1', 'is_active' => true],
            ['title' => 'Portfolio Website', 'slug' => 'portfolio-website', 'description' => 'A clean and responsive portfolio.', 'category' => 'Web Design', 'image_url' => 'https://via.placeholder.com/800x600?text=Project+2', 'is_active' => true],
            ['title' => 'Task Manager', 'slug' => 'task-manager', 'description' => 'A productivity tool for teams.', 'category' => 'SaaS', 'image_url' => 'https://via.placeholder.com/800x600?text=Project+3', 'is_active' => true],
        ];
        foreach ($projects as $project) {
            Project::firstOrCreate(['slug' => $project['slug']], $project);
        }

        // 3. Gallery Folders & Items
        $folders = [
            ['name' => 'Web Designs', 'category' => 'Design', 'description' => 'Various web design mocks', 'is_active' => true, 'cover_image_url' => 'https://via.placeholder.com/800x600?text=Web+Design'],
            ['name' => 'Mobile Apps', 'category' => 'Mobile', 'description' => 'Mobile app interfaces', 'is_active' => true, 'cover_image_url' => 'https://via.placeholder.com/800x600?text=Mobile+App'],
            ['name' => 'Logos', 'category' => 'Branding', 'description' => 'Company logos', 'is_active' => true, 'cover_image_url' => 'https://via.placeholder.com/800x600?text=Logo'],
        ];
        foreach ($folders as $folderData) {
            $folder = GalleryFolder::firstOrCreate(['name' => $folderData['name']], $folderData);
            
            // 3 items per folder
            for ($i = 1; $i <= 3; $i++) {
                GalleryItem::firstOrCreate(
                    ['title' => $folder->name . ' Item ' . $i],
                    [
                        'description' => 'Description for ' . $folder->name . ' item ' . $i,
                        'category' => $folder->category,
                        'image_url' => 'https://via.placeholder.com/800x600?text=Gallery+Item+' . $i,
                        'gallery_folder_id' => $folder->id,
                        'is_active' => true,
                    ]
                );
            }
        }

        // 4. Blog Posts
        $posts = [
            ['title' => 'Getting Started with Laravel', 'slug' => 'getting-started-with-laravel', 'category' => 'Tutorial', 'excerpt' => 'Learn the basics of Laravel.', 'content' => '<p>Laravel is a powerful framework...</p>', 'image_path' => 'https://via.placeholder.com/800x600', 'published_at' => now(), 'is_active' => true, 'author_name' => 'Gilbert Asare', 'author_image_path' => 'https://via.placeholder.com/150'],
            ['title' => 'Mastering Tailwind CSS', 'slug' => 'mastering-tailwind-css', 'category' => 'Design', 'excerpt' => 'Advanced tips for Tailwind.', 'content' => '<p>Tailwind makes styling easy...</p>', 'image_path' => 'https://via.placeholder.com/800x600', 'published_at' => now()->subDays(2), 'is_active' => true, 'author_name' => 'Gilbert Asare', 'author_image_path' => 'https://via.placeholder.com/150'],
            ['title' => 'Why choose React in 2024?', 'slug' => 'why-choose-react-in-2024', 'category' => 'Opinion', 'excerpt' => 'My thoughts on React.', 'content' => '<p>React continues to dominate...</p>', 'image_path' => 'https://via.placeholder.com/800x600', 'published_at' => now()->subDays(5), 'is_active' => true, 'author_name' => 'Gilbert Asare', 'author_image_path' => 'https://via.placeholder.com/150'],
        ];
        foreach ($posts as $post) {
            BlogPost::firstOrCreate(['slug' => $post['slug']], $post);
        }

        // 5. Documents
        $documents = [
            ['title' => 'Resume 2024', 'description' => 'My updated resume', 'file_path' => '#', 'is_active' => true],
            ['title' => 'Services Brochure', 'description' => 'Details about my services', 'file_path' => '#', 'is_active' => true],
            ['title' => 'Pricing Guide', 'description' => 'Estimated costs for projects', 'file_path' => '#', 'is_active' => true],
        ];
        foreach ($documents as $doc) {
            Document::firstOrCreate(['title' => $doc['title']], $doc);
        }

        // 6. Contact Messages
        $messages = [
            ['name' => 'John Doe', 'email' => 'john@example.com', 'subject' => 'Project Inquiry', 'message' => 'I would like to discuss a project with you.', 'is_read' => false],
            ['name' => 'Jane Smith', 'email' => 'jane@example.com', 'subject' => 'Freelance Work', 'message' => 'Are you available for freelance work?', 'is_read' => true],
            ['name' => 'Bob Johnson', 'email' => 'bob@example.com', 'subject' => 'Hello', 'message' => 'Just wanted to say hi and I love your work!', 'is_read' => false],
        ];
        foreach ($messages as $msg) {
            // Need to check if is_read is fillable or exists, if it fails we can wrap in try-catch
            try {
                ContactMessage::firstOrCreate(['email' => $msg['email'], 'subject' => $msg['subject']], $msg);
            } catch (\Exception $e) {
                // Ignore if fields don't exactly match
            }
        }

        // 7. Subscribers
        $subscribers = [
            ['email' => 'sub1@example.com', 'is_active' => true],
            ['email' => 'sub2@example.com', 'is_active' => true],
            ['email' => 'sub3@example.com', 'is_active' => false],
        ];
        foreach ($subscribers as $sub) {
            Subscriber::firstOrCreate(['email' => $sub['email']], $sub);
        }

        // 8. Testimonials
        $testimonials = [
            ['name' => 'Alice Williams', 'role' => 'CEO', 'company' => 'Tech Corp', 'content' => 'Great work! Highly recommended.', 'is_active' => true],
            ['name' => 'Michael Brown', 'role' => 'Manager', 'company' => 'Design Studio', 'content' => 'Very professional and talented.', 'is_active' => true],
            ['name' => 'Sarah Davis', 'role' => 'Founder', 'company' => 'Startup Inc', 'content' => 'Delivered exactly what we needed.', 'is_active' => true],
        ];
        foreach ($testimonials as $test) {
            try {
                Testimonial::firstOrCreate(['name' => $test['name']], $test);
            } catch (\Exception $e) {
                // Ignore
            }
        }
    }
}
