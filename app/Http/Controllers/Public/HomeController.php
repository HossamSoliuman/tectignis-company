<?php

namespace App\Http\Controllers\Public;

use App\Enums\Pillar;
use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Brand;
use App\Models\CaseStudy;
use App\Models\GlobalAdvantage;
use App\Models\Industry;
use App\Models\ProcessStep;
use App\Models\Setting;
use App\Models\TechStack;
use App\Models\Testimonial;
use App\Models\WhyChooseFeature;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * The homepage, in the section order defined by spec §4.3.
     */
    public function __invoke(): View
    {
        $data = Cache::rememberForever('site.home', fn (): array => [
            'brands' => Brand::active()->ordered()->get(),
            'whyChooseFeatures' => WhyChooseFeature::active()->ordered()->get(),
            'caseStudies' => CaseStudy::with('category')->active()->ordered()->limit(6)->get(),
            'industries' => Industry::active()->ordered()->get(),
            'processSteps' => ProcessStep::active()->ordered()->get(),
            'techGroups' => TechStack::active()->shownOnHome()->ordered()->get()
                ->groupBy(fn (TechStack $tech): string => $tech->category->value),
            'testimonials' => Testimonial::active()->ordered()->get(),
            'globalAdvantages' => GlobalAdvantage::active()->ordered()->get(),
            'recentPosts' => BlogPost::published()->latest('published_at')->limit(3)->get(),
        ]);

        return view('public.home', $data + [
            'pillars' => Pillar::cases(),
            'settings' => Setting::values(),
        ]);
    }
}
