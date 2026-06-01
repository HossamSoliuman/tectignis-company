<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\CaseStudy;
use App\Models\Service;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $services = Service::active()->ordered()->get();
        $posts = BlogPost::published()->latest('published_at')->get();
        $caseStudies = CaseStudy::active()->ordered()->get();

        $staticPages = [
            ['url' => route('home'), 'changefreq' => 'weekly', 'priority' => '1.0'],
            ['url' => route('about'), 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['url' => route('capabilities.index'), 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['url' => route('blog.index'), 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['url' => route('case-studies.index'), 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['url' => route('contact'), 'changefreq' => 'yearly', 'priority' => '0.6'],
            ['url' => route('careers'), 'changefreq' => 'monthly', 'priority' => '0.6'],
        ];

        $content = view('sitemap', compact('staticPages', 'services', 'posts', 'caseStudies'))->render();

        return response($content, 200)->header('Content-Type', 'application/xml');
    }
}
