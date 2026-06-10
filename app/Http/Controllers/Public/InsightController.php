<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Insight;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class InsightController extends Controller
{
    public function index(Request $request): View
    {
        $topic = (string) $request->query('topic', '');

        $insights = Insight::published()
            ->when($topic !== '', fn ($query) => $query->where('topic', $topic))
            ->ordered()
            ->paginate(9)
            ->withQueryString();

        return view('public.insights.index', [
            'insights' => $insights,
            'activeTopic' => $topic,
            'topics' => $this->topicsWithCounts(),
        ]);
    }

    public function show(string $slug): View
    {
        $insight = Insight::published()->where('slug', $slug)->firstOrFail();
        $popularInsights = Insight::published()
            ->whereKeyNot($insight->id)
            ->latest('published_at')
            ->limit(4)
            ->get();

        return view('public.insights.show', [
            'insight' => $insight,
            'popularInsights' => $popularInsights,
            'topics' => $this->topicsWithCounts(),
        ]);
    }

    /** @return Collection<string, int> */
    private function topicsWithCounts(): Collection
    {
        return Insight::published()
            ->whereNotNull('topic')
            ->selectRaw('topic, count(*) as total')
            ->groupBy('topic')
            ->orderBy('topic')
            ->pluck('total', 'topic');
    }
}
