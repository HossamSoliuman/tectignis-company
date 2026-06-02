<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Setting;
use Illuminate\Contracts\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        return view('public.about');
    }

    public function careers(): View
    {
        return view('public.careers');
    }

    public function technologyInsights(): View
    {
        return view('public.technology-insights');
    }

    public function downloads(): View
    {
        return view('public.downloads');
    }

    public function faqs(): View
    {
        return view('public.faqs');
    }

    public function legal(string $slug): View
    {
        $titles = [
            'privacy-policy' => 'Privacy Policy',
            'terms-and-conditions' => 'Terms and Conditions',
            'refund-policy' => 'Refund Policy',
        ];

        $title = $titles[$slug] ?? ucwords(str_replace('-', ' ', $slug));
        $content = Setting::get('legal_'.$slug);

        return view('public.legal', compact('slug', 'title', 'content'));
    }

    public function show(Page $page): View
    {
        abort_unless($page->is_active, 404);

        return view('public.pages.show', compact('page'));
    }
}
