<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\BlogPost;
use App\Models\ContactMessage;
use App\Models\Skill;
use App\Models\Document;
use App\Models\Award;
use App\Models\Certification;
use App\Models\Education;
use App\Models\GalleryFolder;

class SearchController extends Controller
{
    public function page()
    {
        return view('admin.pages.search');
    }

    public function search(Request $request)
    {
        $q = trim($request->get('q', ''));

        if (strlen($q) < 2) {
            return response()->json(['results' => [], 'total' => 0, 'query' => $q]);
        }

        $results = [];
        $total   = 0;

        // Projects
        $projects = Project::where('title', 'like', "%{$q}%")
            ->orWhere('description', 'like', "%{$q}%")
            ->orWhere('category', 'like', "%{$q}%")
            ->limit(5)->get();

        if ($projects->isNotEmpty()) {
            $results[] = [
                'group' => 'Projects',
                'icon'  => 'fas fa-layer-group',
                'color' => '#6366f1',
                'items' => $projects->map(fn($p) => [
                    'title'    => $p->title,
                    'subtitle' => $p->category ?? $p->description,
                    'url'      => route('admin.projects'),
                    'icon'     => 'fas fa-layer-group',
                ]),
            ];
            $total += $projects->count();
        }

        // Blog Posts
        $posts = BlogPost::where('title', 'like', "%{$q}%")
            ->orWhere('excerpt', 'like', "%{$q}%")
            ->orWhere('category', 'like', "%{$q}%")
            ->limit(5)->get();

        if ($posts->isNotEmpty()) {
            $results[] = [
                'group' => 'Blog Posts',
                'icon'  => 'fas fa-pen-nib',
                'color' => '#ec4899',
                'items' => $posts->map(fn($p) => [
                    'title'    => $p->title,
                    'subtitle' => $p->category ?? $p->excerpt,
                    'url'      => route('admin.blog-posts'),
                    'icon'     => 'fas fa-pen-nib',
                ]),
            ];
            $total += $posts->count();
        }

        // Messages
        $messages = ContactMessage::where('name', 'like', "%{$q}%")
            ->orWhere('email', 'like', "%{$q}%")
            ->orWhere('subject', 'like', "%{$q}%")
            ->orWhere('message', 'like', "%{$q}%")
            ->limit(5)->get();

        if ($messages->isNotEmpty()) {
            $results[] = [
                'group' => 'Messages',
                'icon'  => 'fas fa-envelope',
                'color' => '#f59e0b',
                'items' => $messages->map(fn($m) => [
                    'title'    => $m->name . ' — ' . ($m->subject ?? 'No subject'),
                    'subtitle' => $m->email,
                    'url'      => route('admin.messages.show', $m),
                    'icon'     => 'fas fa-envelope',
                ]),
            ];
            $total += $messages->count();
        }

        // Skills
        $skills = Skill::where('name', 'like', "%{$q}%")
            ->orWhere('category', 'like', "%{$q}%")
            ->orWhere('sub_category', 'like', "%{$q}%")
            ->limit(5)->get();

        if ($skills->isNotEmpty()) {
            $results[] = [
                'group' => 'Skills',
                'icon'  => 'fas fa-code',
                'color' => '#10b981',
                'items' => $skills->map(fn($s) => [
                    'title'    => $s->name,
                    'subtitle' => trim($s->category . ($s->sub_category ? ' › ' . $s->sub_category : '')),
                    'url'      => route('admin.skills'),
                    'icon'     => 'fas fa-code',
                ]),
            ];
            $total += $skills->count();
        }

        // Documents
        $docs = Document::where('title', 'like', "%{$q}%")
            ->orWhere('description', 'like', "%{$q}%")
            ->limit(5)->get();

        if ($docs->isNotEmpty()) {
            $results[] = [
                'group' => 'Files',
                'icon'  => 'fas fa-file-alt',
                'color' => '#3b82f6',
                'items' => $docs->map(fn($d) => [
                    'title'    => $d->title,
                    'subtitle' => $d->description ?? 'Document',
                    'url'      => route('admin.my-files'),
                    'icon'     => 'fas fa-file-alt',
                ]),
            ];
            $total += $docs->count();
        }

        // Awards
        $awards = Award::where('title', 'like', "%{$q}%")
            ->orWhere('issuer', 'like', "%{$q}%")
            ->orWhere('description', 'like', "%{$q}%")
            ->limit(5)->get();

        if ($awards->isNotEmpty()) {
            $results[] = [
                'group' => 'Awards',
                'icon'  => 'fas fa-trophy',
                'color' => '#f97316',
                'items' => $awards->map(fn($a) => [
                    'title'    => $a->title,
                    'subtitle' => $a->issuer ?? '',
                    'url'      => route('admin.awards'),
                    'icon'     => 'fas fa-trophy',
                ]),
            ];
            $total += $awards->count();
        }

        // Certifications
        $certs = Certification::where('name', 'like', "%{$q}%")
            ->orWhere('issuer', 'like', "%{$q}%")
            ->limit(5)->get();

        if ($certs->isNotEmpty()) {
            $results[] = [
                'group' => 'Certifications',
                'icon'  => 'fas fa-certificate',
                'color' => '#8b5cf6',
                'items' => $certs->map(fn($c) => [
                    'title'    => $c->name,
                    'subtitle' => $c->issuer ?? '',
                    'url'      => route('admin.certifications'),
                    'icon'     => 'fas fa-certificate',
                ]),
            ];
            $total += $certs->count();
        }

        // Education
        $edu = Education::where('degree', 'like', "%{$q}%")
            ->orWhere('institution', 'like', "%{$q}%")
            ->orWhere('description', 'like', "%{$q}%")
            ->limit(5)->get();

        if ($edu->isNotEmpty()) {
            $results[] = [
                'group' => 'Education',
                'icon'  => 'fas fa-graduation-cap',
                'color' => '#06b6d4',
                'items' => $edu->map(fn($e) => [
                    'title'    => $e->degree,
                    'subtitle' => $e->institution ?? '',
                    'url'      => route('admin.education'),
                    'icon'     => 'fas fa-graduation-cap',
                ]),
            ];
            $total += $edu->count();
        }

        // Gallery Folders
        $folders = GalleryFolder::where('name', 'like', "%{$q}%")
            ->orWhere('category', 'like', "%{$q}%")
            ->limit(5)->get();

        if ($folders->isNotEmpty()) {
            $results[] = [
                'group' => 'Gallery',
                'icon'  => 'fas fa-images',
                'color' => '#14b8a6',
                'items' => $folders->map(fn($f) => [
                    'title'    => $f->name,
                    'subtitle' => $f->category ?? 'Gallery Folder',
                    'url'      => route('admin.gallery-folders.show', $f),
                    'icon'     => 'fas fa-folder-open',
                ]),
            ];
            $total += $folders->count();
        }

        return response()->json([
            'results' => $results,
            'total'   => $total,
            'query'   => $q,
        ]);
    }
}
