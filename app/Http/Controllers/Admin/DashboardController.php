<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\CaseStudy;
use App\Models\Lead;
use App\Models\PageVisit;
use App\Models\Service;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    /**
     * Number of days of history shown in the dashboard charts.
     */
    private const TREND_DAYS = 30;

    public function __invoke(): View
    {
        $stats = [
            'Leads' => Lead::count(),
            'Blog Posts' => BlogPost::count(),
            'Services' => Service::count(),
            'Case Studies' => CaseStudy::count(),
        ];

        $since = now()->startOfDay()->subDays(self::TREND_DAYS - 1);

        return view('admin.dashboard', [
            'stats' => $stats,
            'visitsTrend' => $this->dailyTrend(PageVisit::query()->where('created_at', '>=', $since)->pluck('created_at')),
            'leadsTrend' => $this->dailyTrend(Lead::query()->where('created_at', '>=', $since)->pluck('created_at')),
            'topPages' => $this->topPages($since),
            'visitsTotal' => PageVisit::where('created_at', '>=', $since)->count(),
            'leadsTotal' => Lead::where('created_at', '>=', $since)->count(),
        ]);
    }

    /**
     * Build a continuous day-by-day count over the trend window, oldest first.
     *
     * @param  Collection<int, Carbon>  $timestamps
     * @return array<int, array{label: string, date: string, value: int}>
     */
    private function dailyTrend(Collection $timestamps): array
    {
        $counts = $timestamps
            ->groupBy(fn (Carbon $timestamp): string => $timestamp->toDateString())
            ->map(fn (Collection $group): int => $group->count());

        return Collection::times(self::TREND_DAYS, function (int $offset) use ($counts): array {
            $day = now()->startOfDay()->subDays(self::TREND_DAYS - $offset);

            return [
                'label' => $day->format('M j'),
                'date' => $day->toDateString(),
                'value' => $counts->get($day->toDateString(), 0),
            ];
        })->all();
    }

    /**
     * Most visited paths within the trend window.
     *
     * @return array<int, array{path: string, value: int}>
     */
    private function topPages(Carbon $since): array
    {
        return PageVisit::query()
            ->where('created_at', '>=', $since)
            ->selectRaw('path, count(*) as visits')
            ->groupBy('path')
            ->orderByDesc('visits')
            ->limit(6)
            ->get()
            ->map(fn (PageVisit $visit): array => [
                'path' => $visit->path,
                'value' => (int) $visit->visits,
            ])
            ->all();
    }
}
