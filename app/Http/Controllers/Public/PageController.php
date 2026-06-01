<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
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
}
