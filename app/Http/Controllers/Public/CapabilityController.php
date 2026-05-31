<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class CapabilityController extends Controller
{
    public function index(): View
    {
        return view('public.capabilities.index');
    }
}
