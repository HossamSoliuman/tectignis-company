<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $stats = [
            'Leads' => 0,
            'Blog Posts' => 0,
            'Services' => 0,
            'Case Studies' => 0,
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
