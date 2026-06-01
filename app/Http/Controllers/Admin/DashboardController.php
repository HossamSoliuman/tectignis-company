<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\CaseStudy;
use App\Models\Lead;
use App\Models\Service;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $stats = [
            'Leads' => Lead::count(),
            'Blog Posts' => BlogPost::count(),
            'Services' => Service::count(),
            'Case Studies' => CaseStudy::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
