<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\UploadsFiles;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreInsightRequest;
use App\Http\Requests\Admin\UpdateInsightRequest;
use App\Models\Insight;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class InsightController extends Controller
{
    use UploadsFiles;

    public function index(): View
    {
        $insights = Insight::ordered()->get();

        return view('admin.insights.index', compact('insights'));
    }

    public function create(): View
    {
        return view('admin.insights.create', ['topics' => $this->topics()]);
    }

    public function store(StoreInsightRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = ($data['slug'] ?? null) ?: Str::slug($data['title']);

        $this->syncUpload($request, $data, 'image', null, 'insights');

        Insight::create($data);

        return redirect()->route('admin.insights.index')->with('status', 'Insight created.');
    }

    public function edit(Insight $insight): View
    {
        return view('admin.insights.edit', ['insight' => $insight, 'topics' => $this->topics()]);
    }

    public function update(UpdateInsightRequest $request, Insight $insight): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = ($data['slug'] ?? null) ?: Str::slug($data['title']);

        $this->syncUpload($request, $data, 'image', $insight->image, 'insights');

        $insight->update($data);

        return redirect()->route('admin.insights.index')->with('status', 'Insight updated.');
    }

    public function destroy(Insight $insight): RedirectResponse
    {
        $this->deleteUpload($insight->image);
        $insight->delete();

        return redirect()->route('admin.insights.index')->with('status', 'Insight deleted.');
    }

    /**
     * Distinct topics already in use, offered as datalist suggestions in the form.
     *
     * @return list<string>
     */
    private function topics(): array
    {
        return Insight::query()->whereNotNull('topic')->distinct()->orderBy('topic')->pluck('topic')->all();
    }
}
