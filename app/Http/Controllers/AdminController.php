<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function overview()
    {
        $projectsCount = \App\Models\Project::count();
        $projectsThisMonth = \App\Models\Project::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
        $projectsLastMonth = \App\Models\Project::whereMonth('created_at', now()->subMonth()->month)->whereYear('created_at', now()->subMonth()->year)->count();
        $projectsDiff = $projectsThisMonth - $projectsLastMonth;
        $projectsDiffStr = ($projectsDiff > 0 ? '+' : '') . $projectsDiff . ' this mo';

        $skillsCount = \App\Models\Skill::count();
        $skillsCategoriesCount = \App\Models\Skill::distinct('category')->count();

        $certificationsCount = \App\Models\Certification::count();
        $latestCertification = \App\Models\Certification::latest()->first();

        $galleryCount = \App\Models\GalleryItem::count();
        
        $messagesCount = \App\Models\ContactMessage::count();
        $blogPostsCount = \App\Models\BlogPost::count();
        $journeyCount = \App\Models\Experience::count() + \App\Models\Education::count();

        $recentActivity = \App\Models\ActivityLog::latest()->take(5)->get();

        $storageBytes = 0;
        $filesStored = 0;
        
        // 1. Count only uploaded files for the text
        $directories = [public_path('documents'), public_path('images'), public_path('gallery'), public_path('uploads')];
        foreach ($directories as $dir) {
            if (\Illuminate\Support\Facades\File::exists($dir)) {
                $filesStored += count(\Illuminate\Support\Facades\File::allFiles($dir));
            }
        }

        // 2. Calculate TOTAL project size (System + DB + Uploads)
        try {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator(base_path(), \FilesystemIterator::SKIP_DOTS)
            );
            foreach ($iterator as $file) {
                $storageBytes += $file->getSize();
            }
        } catch (\Exception $e) {
            // Fallback just in case of permission issues
        }

        $storageMB = number_format($storageBytes / 1048576, 1);
        $storagePercent = number_format(($storageMB / 1024) * 100, 1); // out of 1GB

        // For Bar Chart
        $months = [];
        $projectData = [];
        $skillData = [];
        $blogData = [];
        for ($monthNum = 1; $monthNum <= 12; $monthNum++) {
            $monthDate = now()->month($monthNum);
            $months[] = $monthDate->format('M');
            $projectData[] = \App\Models\Project::whereMonth('created_at', $monthNum)->whereYear('created_at', now()->year)->count();
            $skillData[] = \App\Models\Skill::whereMonth('created_at', $monthNum)->whereYear('created_at', now()->year)->count();
            $blogData[] = \App\Models\BlogPost::whereMonth('created_at', $monthNum)->whereYear('created_at', now()->year)->count();
        }

        // For Pie Chart (Projects by Category)
        $projectsByCategory = \App\Models\Project::select('category', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
                                ->groupBy('category')
                                ->pluck('total', 'category')
                                ->toArray();
        $pieLabels = array_keys($projectsByCategory);
        $pieData = array_values($projectsByCategory);
        
        // Ensure pie chart has data even if empty
        if (empty($pieLabels)) {
            $pieLabels = ['No Data'];
            $pieData = [1];
        }

        return view('admin.pages.overview', compact(
            'projectsCount', 'projectsDiffStr',
            'skillsCount', 'skillsCategoriesCount',
            'certificationsCount', 'latestCertification',
            'galleryCount', 'messagesCount', 'blogPostsCount', 'journeyCount',
            'recentActivity',
            'storageMB', 'storagePercent', 'filesStored',
            'months', 'projectData', 'skillData', 'blogData',
            'pieLabels', 'pieData'
        ));
    }

    public function messages()
    {
        return view('admin.pages.messages');
    }

    public function aboutMe()
    {
        return view('admin.pages.about-me');
    }

    public function skills()
    {
        return view('admin.pages.skills');
    }

    public function projects()
    {
        return view('admin.pages.projects');
    }

    public function certifications()
    {
        return view('admin.pages.certifications');
    }

    public function awards()
    {
        return view('admin.pages.awards');
    }

    public function myFiles()
    {
        return view('admin.pages.my-files');
    }

    public function blogPosts()
    {
        return view('admin.pages.blog-posts');
    }

    public function education()
    {
        return view('admin.pages.education');
    }

    public function journey()
    {
        return view('admin.pages.journey');
    }

    public function gallery()
    {
        return view('admin.pages.gallery');
    }

    public function notifications()
    {
        return view('admin.pages.notifications');
    }

    public function webAdmin()
    {
        $logs = ActivityLog::latest()->paginate(50);

        return view('admin.pages.web-admin', compact('logs'));
    }

    public function settings()
    {
        return view('admin.pages.settings');
    }
}
