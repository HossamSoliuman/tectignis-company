<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\LandingPage;
use Illuminate\Contracts\View\View;

class LandingPageController extends Controller
{
    /**
     * A focused Google Ads landing page: minimal navigation and an embedded
     * enquiry form preset to the page's service.
     */
    public function show(string $slug): View
    {
        $landingPage = LandingPage::active()
            ->with('service')
            ->where('slug', $slug)
            ->firstOrFail();

        return view('public.landing-pages.show', [
            'landingPage' => $landingPage,
            'caseStudies' => $landingPage->caseStudies(),
        ]);
    }
}
