<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Capability;
use App\Models\CaseStudy;
use App\Models\Industry;
use App\Models\Insight;
use App\Models\JobOpening;
use App\Models\LandingPage;
use App\Models\Page;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Solution;
use App\Support\Locales;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $capabilities = Capability::active()->ordered()->get();
        $services = Service::active()->ordered()->get();
        $landingPages = LandingPage::active()->ordered()->get(['id', 'slug', 'updated_at']);
        $solutions = Solution::active()->ordered()->get();
        $industries = Industry::active()->ordered()->get();
        $pages = Page::active()->ordered()->get();
        $posts = BlogPost::published()->latest('published_at')->get();
        $insights = Insight::published()->latest('published_at')->get();
        $caseStudies = CaseStudy::active()->ordered()->get();
        $jobOpenings = JobOpening::open()->ordered()->get(['id', 'slug', 'updated_at']);

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

        $languagePages = $this->languagePages($services, $landingPages);

        $content = view('sitemap', compact(
            'staticPages', 'languagePages', 'capabilities', 'services', 'landingPages', 'solutions', 'industries', 'pages', 'posts', 'insights', 'caseStudies', 'jobOpenings'
        ))->render();

        return response($content, 200)->header('Content-Type', 'application/xml');
    }

    /**
     * The translated pages in every enabled language other than English.
     *
     * @param  Collection<int, Service>  $services
     * @param  Collection<int, LandingPage>  $landingPages
     * @return list<array{url: string, changefreq: string, priority: string}>
     */
    private function languagePages(Collection $services, Collection $landingPages): array
    {
        $pages = [];

        foreach (array_diff(Locales::enabled(), [Locales::default()]) as $code) {
            $pages[] = ['url' => Locales::route('home', [], $code), 'changefreq' => 'weekly', 'priority' => '1.0'];
            $pages[] = ['url' => Locales::route('contact', [], $code), 'changefreq' => 'yearly', 'priority' => '0.6'];

            foreach ($services as $service) {
                if (Locales::isTranslatedPage('services.show', $service->slug)) {
                    $pages[] = ['url' => Locales::route('services.show', $service->slug, $code), 'changefreq' => 'monthly', 'priority' => '0.8'];
                }
            }

            foreach ($landingPages as $landingPage) {
                $pages[] = ['url' => Locales::route('landing-pages.show', $landingPage->slug, $code), 'changefreq' => 'monthly', 'priority' => '0.8'];
            }

            foreach (config('locales.translated_pages')['legal.show'] as $legal) {
                $pages[] = ['url' => Locales::route('legal.show', $legal, $code), 'changefreq' => 'yearly', 'priority' => '0.3'];
            }
        }

        return $pages;
    }

    public function robots(): Response
    {
        $content = Setting::get('robots_txt', "User-agent: *\nAllow: /")."\nSitemap: ".url('/sitemap.xml');

        return response($content, 200)->header('Content-Type', 'text/plain');
    }
}
