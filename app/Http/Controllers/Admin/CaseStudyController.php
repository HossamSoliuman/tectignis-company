<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CaseStudyStatus;
use App\Enums\ProjectStatus;
use App\Http\Controllers\Admin\Concerns\PaginatesTables;
use App\Http\Controllers\Admin\Concerns\UploadsFiles;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Public\CaseStudyController as PublicCaseStudyController;
use App\Http\Requests\Admin\StoreCaseStudyRequest;
use App\Http\Requests\Admin\UpdateCaseStudyRequest;
use App\Models\CaseStudy;
use App\Models\CaseStudyCategory;
use App\Models\Industry;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * The portfolio: every project management approves for public display, with
 * a draft → preview → publish → archive workflow.
 */
class CaseStudyController extends Controller
{
    use PaginatesTables;
    use UploadsFiles;

    private const UPLOAD_FOLDER = 'case-studies';

    public function index(Request $request): View
    {
        $status = CaseStudyStatus::tryFrom((string) $request->query('status'));
        $projectStatus = ProjectStatus::tryFrom((string) $request->query('project_status'));
        $categoryId = (int) $request->query('category');

        $query = CaseStudy::with('category')
            ->when($status, fn (Builder $query) => $query->where('status', $status))
            ->when($projectStatus, fn (Builder $query) => $query->where('project_status', $projectStatus))
            ->when($categoryId, fn (Builder $query) => $query->where('case_study_category_id', $categoryId))
            ->when($request->boolean('featured'), fn (Builder $query) => $query->where('is_featured', true))
            ->ordered();

        return view('admin.case-studies.index', [
            'caseStudies' => $this->paginateTable($request, $query, ['title', 'client_name', 'slug', 'location']),
            'categories' => CaseStudyCategory::ordered()->get(['id', 'name']),
            'statusCounts' => CaseStudy::query()->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
        ]);
    }

    public function create(): View
    {
        return view('admin.case-studies.create', $this->formOptions());
    }

    public function store(StoreCaseStudyRequest $request): RedirectResponse
    {
        $data = $this->prepare($request, $request->validated());
        $data['slug'] = $this->uniqueSlug(($data['slug'] ?? null) ?: Str::slug($data['title']));

        $this->syncUpload($request, $data, 'image', null, self::UPLOAD_FOLDER);

        $caseStudy = CaseStudy::create($data);

        return redirect()->route('admin.case-studies.edit', $caseStudy)->with('status', 'Case study created.');
    }

    public function show(CaseStudy $caseStudy): View
    {
        return app(PublicCaseStudyController::class)->render($caseStudy, preview: true);
    }

    public function edit(CaseStudy $caseStudy): View
    {
        return view('admin.case-studies.edit', ['caseStudy' => $caseStudy, ...$this->formOptions()]);
    }

    public function update(UpdateCaseStudyRequest $request, CaseStudy $caseStudy): RedirectResponse
    {
        $data = $this->prepare($request, $request->validated(), $caseStudy);
        $data['slug'] = ($data['slug'] ?? null) ?: $caseStudy->slug;

        $this->syncUpload($request, $data, 'image', $caseStudy->image, self::UPLOAD_FOLDER);

        $caseStudy->update($data);

        return redirect()->route('admin.case-studies.edit', $caseStudy)->with('status', 'Case study updated.');
    }

    public function destroy(CaseStudy $caseStudy): RedirectResponse
    {
        $this->deleteUpload($caseStudy->image);

        foreach ($caseStudy->galleryImages() as $item) {
            $this->deleteUpload($item['image']);
        }

        $caseStudy->delete();

        return redirect()->route('admin.case-studies.index')->with('status', 'Case study deleted.');
    }

    /**
     * Normalise validated input: the featured flag and the screenshot gallery
     * (kept items with their alt text, removed items deleted, new uploads
     * appended).
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function prepare(Request $request, array $data, ?CaseStudy $caseStudy = null): array
    {
        $data['is_featured'] = $request->boolean('is_featured');
        $data['gallery'] = $this->syncGallery($request, $data['gallery'] ?? [], $caseStudy);

        unset($data['gallery_uploads']);

        return $data;
    }

    /**
     * @param  array<int, array{image: string, alt?: ?string, remove?: bool}>  $submitted
     * @return list<array{image: string, alt: ?string}>
     */
    private function syncGallery(Request $request, array $submitted, ?CaseStudy $caseStudy): array
    {
        $existing = collect($caseStudy?->gallery ?? [])->pluck('image')->filter()->all();
        $gallery = [];

        foreach ($submitted as $item) {
            // Only images already stored on this project can be kept.
            if (! in_array($item['image'], $existing, true)) {
                continue;
            }

            if (! empty($item['remove'])) {
                $this->deleteUpload($item['image']);

                continue;
            }

            $gallery[] = ['image' => $item['image'], 'alt' => $item['alt'] ?? null];
        }

        foreach ((array) $request->file('gallery_uploads', []) as $file) {
            $gallery[] = ['image' => $this->storeUpload($file, self::UPLOAD_FOLDER), 'alt' => null];
        }

        return $gallery;
    }

    private function uniqueSlug(string $base): string
    {
        $base = $base !== '' ? $base : 'project';
        $slug = $base;
        $suffix = 2;

        while (CaseStudy::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }

    /**
     * @return array<string, mixed>
     */
    private function formOptions(): array
    {
        return [
            'categories' => CaseStudyCategory::ordered()->get(['id', 'name']),
            'industries' => Industry::orderBy('name')->get(['id', 'name']),
            'services' => Service::orderBy('title')->get(['id', 'title']),
            'testimonials' => Testimonial::ordered()->get(['id', 'name', 'quote']),
        ];
    }
}
