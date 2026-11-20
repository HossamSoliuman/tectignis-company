<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\PaginatesTables;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\CaseStudy;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use PaginatesTables;

    public function index(Request $request): View
    {
        $products = $this->paginateTable($request, Product::ordered(), ['name', 'description']);

        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        return view('admin.products.create', ['caseStudies' => $this->caseStudyOptions()]);
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        Product::create($request->validated());

        return redirect()->route('admin.products.index')->with('status', 'Product created.');
    }

    public function edit(Product $product): View
    {
        return view('admin.products.edit', ['product' => $product, 'caseStudies' => $this->caseStudyOptions()]);
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $product->update($request->validated());

        return redirect()->route('admin.products.index')->with('status', 'Product updated.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('status', 'Product deleted.');
    }

    /**
     * Published case studies a product can link to.
     *
     * @return array<int, string> id => title
     */
    private function caseStudyOptions(): array
    {
        return CaseStudy::published()->orderBy('title')->pluck('title', 'id')->all();
    }
}
