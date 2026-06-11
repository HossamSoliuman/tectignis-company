<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Brand;
use App\Models\Capability;
use App\Models\CaseStudy;
use App\Models\GlobalAdvantage;
use App\Models\Industry;
use App\Models\OfficeLocation;
use App\Models\ProcessStep;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Solution;
use App\Models\Stat;
use App\Models\TechStack;
use App\Models\Testimonial;
use App\Models\WhyChooseFeature;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        return view('public.home', [
            'capabilities' => Capability::active()->ordered()->get(),
            'solutions' => Solution::active()->ordered()->get(),
            'industries' => Industry::active()->ordered()->get(),
            'stats' => Stat::active()->ordered()->get(),
            'techStacks' => TechStack::active()->shownOnHome()->ordered()->get(),
            'services' => Service::active()->ordered()->get(),
            'caseStudies' => CaseStudy::with('category')->active()->ordered()->get(),
            'testimonials' => Testimonial::active()->ordered()->get(),
            'brands' => Brand::active()->ordered()->get(),
            'whyChooseFeatures' => WhyChooseFeature::active()->ordered()->get(),
            'indiaLocations' => OfficeLocation::where('region', 'india')->active()->ordered()->get(),
            'globalLocations' => OfficeLocation::where('region', 'global')->active()->ordered()->get(),
            'globalAdvantages' => GlobalAdvantage::active()->ordered()->get(),
            'processSteps' => ProcessStep::active()->ordered()->get(),
            'settings' => Setting::pluck('value', 'key'),
            'recentPosts' => BlogPost::published()->latest('published_at')->limit(3)->get(),
        ]);
    }
}
