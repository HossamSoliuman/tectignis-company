<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\FaqCategory;
use App\Models\JobOpening;
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
        $jobOpenings = JobOpening::active()->ordered()->get();

        return view('public.careers', compact('jobOpenings'));
    }

    public function faqs(): View
    {
        $faqCategories = FaqCategory::ordered()
            ->with(['faqs' => fn ($query) => $query->active()->ordered()])
            ->get()
            ->filter(fn (FaqCategory $category) => $category->faqs->isNotEmpty())
            ->values();

        return view('public.faqs', compact('faqCategories'));
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
