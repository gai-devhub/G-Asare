<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Project;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $siteUrl = url('/');

        $projects = Project::all();
        $posts = BlogPost::all();

        $xml  = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        $static = [
            ['loc' => url('/'), 'changefreq' => 'weekly', 'priority' => '1.0'],
            ['loc' => url('/about'), 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => url('/projects'), 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => url('/edu&certs'), 'changefreq' => 'monthly', 'priority' => '0.6'],
            ['loc' => url('/news'), 'changefreq' => 'weekly', 'priority' => '0.7'],
        ];

        foreach ($static as $page) {
            $xml .= '<url>';
            $xml .= '<loc>' . e($page['loc']) . '</loc>';
            $xml .= '<changefreq>' . $page['changefreq'] . '</changefreq>';
            $xml .= '<priority>' . $page['priority'] . '</priority>';
            $xml .= '</url>';
        }

        foreach ($projects as $project) {
            $loc = url('/projects');
            if (isset($project->slug)) {
                $loc = url('/news/' . $project->slug);
            }
            $xml .= '<url><loc>' . e($loc) . '</loc><changefreq>monthly</changefreq><priority>0.6</priority></url>';
        }

        foreach ($posts as $post) {
            if (isset($post->slug)) {
                $xml .= '<url><loc>' . e(url('/news/' . $post->slug)) . '</loc><changefreq>monthly</changefreq><priority>0.6</priority></url>';
            }
        }

        $xml .= '</urlset>';

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}
