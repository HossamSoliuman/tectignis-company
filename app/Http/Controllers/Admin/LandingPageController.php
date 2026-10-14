<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\PaginatesTables;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreLandingPageRequest;
use App\Http\Requests\Admin\UpdateLandingPageRequest;
use App\Models\LandingPage;
use App\Models\Service;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    use PaginatesTables;

    public function index(Request $request): View
    {
        $landingPages = $this->paginateTable($request, LandingPage::with('service:id,title')->ordered(), ['title', 'slug', 'headline']);

        return view('admin.landing-pages.index', compact('landingPages'));
    }

    public function create(): View
    {
        return view('admin.landing-pages.create', ['serviceOptions' => $this->serviceOptions()]);
    }

    public function store(StoreLandingPageRequest $request): RedirectResponse
    {
        $landingPage = LandingPage::create($this->prepare($request->validated()));

        return redirect()->route('admin.landing-pages.edit', $landingPage)->with('status', 'Landing page created.');
    }

    public function edit(LandingPage $landingPage): View
    {
        return view('admin.landing-pages.edit', [
            'landingPage' => $landingPage,
            'serviceOptions' => $this->serviceOptions(),
        ]);
    }

    public function update(UpdateLandingPageRequest $request, LandingPage $landingPage): RedirectResponse
    {
        $landingPage->update($this->prepare($request->validated()));

        return redirect()->route('admin.landing-pages.edit', $landingPage)->with('status', 'Landing page updated.');
    }

    public function destroy(LandingPage $landingPage): RedirectResponse
    {
        $landingPage->delete();

        return redirect()->route('admin.landing-pages.index')->with('status', 'Landing page deleted.');
    }

    /**
     * Drop empty bullets and FAQ rows so the stored JSON stays clean.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function prepare(array $data): array
    {
        $content = $data['content'] ?? [];

        $content['bullets'] = array_values(array_filter(
            array_map(fn (mixed $bullet): string => trim((string) $bullet), $content['bullets'] ?? []),
            fn (string $bullet): bool => $bullet !== '',
        ));

        $content['faq']['items'] = array_values(array_filter(
            $content['faq']['items'] ?? [],
            fn (mixed $item): bool => is_array($item) && filled($item['question'] ?? null),
        ));

        $data['content'] = $content;
        $data['sort_order'] ??= 0;

        return $data;
    }

    /**
     * @return Collection<int, Service>
     */
    private function serviceOptions(): Collection
    {
        return Service::ordered()->get(['id', 'title']);
    }
}
