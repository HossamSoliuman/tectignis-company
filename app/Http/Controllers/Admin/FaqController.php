<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\PaginatesTables;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreFaqRequest;
use App\Http\Requests\Admin\UpdateFaqRequest;
use App\Models\Faq;
use App\Models\FaqCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    use PaginatesTables;

    public function index(Request $request): View
    {
        $faqs = $this->paginateTable($request, Faq::with('category')->orderBy('faq_category_id')->orderBy('sort_order'), ['question', 'answer']);

        return view('admin.faqs.index', compact('faqs'));
    }

    public function create(): View
    {
        return view('admin.faqs.create', ['categories' => FaqCategory::ordered()->get()]);
    }

    public function store(StoreFaqRequest $request): RedirectResponse
    {
        Faq::create($request->validated());

        return redirect()->route('admin.faqs.index')->with('status', 'FAQ created.');
    }

    public function edit(Faq $faq): View
    {
        return view('admin.faqs.edit', ['faq' => $faq, 'categories' => FaqCategory::ordered()->get()]);
    }

    public function update(UpdateFaqRequest $request, Faq $faq): RedirectResponse
    {
        $faq->update($request->validated());

        return redirect()->route('admin.faqs.index')->with('status', 'FAQ updated.');
    }

    public function destroy(Faq $faq): RedirectResponse
    {
        $faq->delete();

        return redirect()->route('admin.faqs.index')->with('status', 'FAQ deleted.');
    }
}
