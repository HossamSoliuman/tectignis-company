<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\PaginatesTables;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreFaqCategoryRequest;
use App\Http\Requests\Admin\UpdateFaqCategoryRequest;
use App\Models\FaqCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FaqCategoryController extends Controller
{
    use PaginatesTables;

    public function index(Request $request): View
    {
        $categories = $this->paginateTable($request, FaqCategory::withCount('faqs')->ordered(), ['name']);

        return view('admin.faq-categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.faq-categories.create');
    }

    public function store(StoreFaqCategoryRequest $request): RedirectResponse
    {
        FaqCategory::create($request->validated());

        return redirect()->route('admin.faq-categories.index')->with('status', 'FAQ category created.');
    }

    public function edit(FaqCategory $faqCategory): View
    {
        return view('admin.faq-categories.edit', compact('faqCategory'));
    }

    public function update(UpdateFaqCategoryRequest $request, FaqCategory $faqCategory): RedirectResponse
    {
        $faqCategory->update($request->validated());

        return redirect()->route('admin.faq-categories.index')->with('status', 'FAQ category updated.');
    }

    public function destroy(FaqCategory $faqCategory): RedirectResponse
    {
        $faqCategory->delete();

        return redirect()->route('admin.faq-categories.index')->with('status', 'FAQ category deleted.');
    }
}
