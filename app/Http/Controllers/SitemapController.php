<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Capability;
use App\Models\CaseStudy;
use App\Models\Industry;
use App\Models\Insight;
use App\Models\Page;
use App\Models\Service;
use App\Models\Solution;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $capabilities = Capability::active()->ordered()->get();
        $services = Service::active()->ordered()->get();
        $solutions = Solution::active()->ordered()->get();
        $industries = Industry::active()->ordered()->get();
        $pages = Page::active()->ordered()->get();
        $posts = BlogPost::published()->latest('published_at')->get();
        $insights = Insight::published()->latest('published_at')->get();
        $caseStudies = CaseStudy::active()->ordered()->get();

        $staticPages = [
            ['url' => route('home'), 'changefreq' => 'weekly', 'priority' => '1.0'],
            ['url' => route('about'), 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['url' => route('capabilities.index'), 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['url' => route('solutions.index'), 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['url' => route('industries.index'), 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['url' => route('blog.index'), 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['url' => route('technology-insights'), 'changefreq' => 'weekly', 'priority' => '0.7'],
            ['url' => route('downloads'), 'changefreq' => 'monthly', 'priority' => '0.6'],
            ['url' => route('faqs'), 'changefreq' => 'monthly', 'priority' => '0.6'],
            ['url' => route('case-studies.index'), 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['url' => route('contact'), 'changefreq' => 'yearly', 'priority' => '0.6'],
            ['url' => route('careers'), 'changefreq' => 'monthly', 'priority' => '0.6'],
        ];

        $content = view('sitemap', compact(
            'staticPages', 'capabilities', 'services', 'solutions', 'industries', 'pages', 'posts', 'insights', 'caseStudies'
        ))->render();

        return response($content, 200)->header('Content-Type', 'application/xml');
    }
}
