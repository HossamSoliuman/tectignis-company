<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\CaseStudy;
use App\Models\CaseStudyCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class CaseStudyController extends Controller
{
    private const PER_PAGE = 12;

    /**
     * The portfolio, filterable by service category. Only categories with a
     * published project are offered, so no filter leads to an empty page.
     */
    public function index(Request $request): View
    {
        $categories = CaseStudyCategory::active()
            ->whereHas('caseStudies', fn (Builder $query) => $query->published())
            ->ordered()
            ->get();

        $category = $categories->firstWhere('id', (int) $request->query('category'));

        $caseStudies = CaseStudy::with('category')
            ->published()
            ->when($category, fn (Builder $query) => $query->where('case_study_category_id', $category->id))
            ->orderByDesc('is_featured')
            ->ordered()
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        return view('public.case-studies.index', compact('caseStudies', 'categories', 'category'));
    }

    public function show(CaseStudy $caseStudy): View
    {
        abort_unless($caseStudy->isPublished(), 404);

        return $this->render($caseStudy);
    }

    /**
     * The case-study page for any project; the admin preview uses it for
     * drafts and archived projects.
     */
    public function render(CaseStudy $caseStudy, bool $preview = false): View
    {
        $caseStudy->loadMissing(['category', 'industry', 'service', 'testimonial']);

        return view('public.case-studies.show', [
            'caseStudy' => $caseStudy,
            'relatedCaseStudies' => $this->related($caseStudy),
            'preview' => $preview,
        ]);
    }

    /**
     * Up to three other published projects, same category first.
     *
     * @return Collection<int, CaseStudy>
     */
    private function related(CaseStudy $caseStudy): Collection
    {
        $query = CaseStudy::with('category')->published()->whereKeyNot($caseStudy->getKey());

        if ($caseStudy->case_study_category_id) {
            $query->orderByRaw('case when case_study_category_id = ? then 0 else 1 end', [$caseStudy->case_study_category_id]);
        }

        return $query->ordered()->limit(3)->get();
    }
}
