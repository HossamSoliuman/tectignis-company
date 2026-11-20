<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Contracts\View\View;

class ProductController extends Controller
{
    /**
     * Every live product with its features, website and published case study.
     */
    public function index(): View
    {
        $products = Product::with(['caseStudy' => fn ($query) => $query->published()])->active()->ordered()->get();

        return view('public.products.index', compact('products'));
    }
}
