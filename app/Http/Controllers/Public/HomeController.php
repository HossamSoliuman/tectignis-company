<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Brand;
use App\Models\CaseStudy;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Testimonial;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        return view('public.home', [
            'services' => Service::active()->ordered()->get(),
            'caseStudies' => CaseStudy::active()->ordered()->get(),
            'testimonials' => Testimonial::active()->ordered()->get(),
            'brands' => Brand::active()->ordered()->get(),
            'settings' => Setting::pluck('value', 'key'),
            'recentPosts' => BlogPost::published()->latest('published_at')->limit(3)->get(),
        ]);
    }
}
