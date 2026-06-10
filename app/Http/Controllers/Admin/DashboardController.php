<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\CaseStudy;
use App\Models\Lead;
use App\Models\Page;
use App\Models\PageVisit;
use App\Models\Service;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
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
        $since = now()->startOfDay()->subDays(self::TREND_DAYS - 1);

        $cards = [
            'Total Leads' => $this->cardStat(Lead::class, $since),
            'Blog Posts' => $this->cardStat(BlogPost::class, $since),
            'Services' => $this->cardStat(Service::class, $since),
            'Case Studies' => $this->cardStat(CaseStudy::class, $since),
            'Pages' => $this->cardStat(Page::class, $since),
        ];

        return view('admin.dashboard', [
            'cards' => $cards,
            'visitsTrend' => $this->dailyTrend(PageVisit::query()->where('created_at', '>=', $since)->pluck('created_at')),
            'visitsTotal' => PageVisit::where('created_at', '>=', $since)->count(),
            'visitsGrowth' => $this->growth(
                PageVisit::where('created_at', '>=', $since)->count(),
                PageVisit::whereBetween('created_at', [$since->copy()->subDays(self::TREND_DAYS), $since])->count(),
            ),
            'topPages' => $this->topPages($since),
            'leadsTotal' => Lead::count(),
            'leadSegments' => $this->leadSegments(),
            'recentLeads' => Lead::latest()->limit(4)->get(),
        ]);
    }

    /**
     * Total count plus growth of the last 30 days versus the 30 days before.
     *
     * @param  class-string<Model>  $model
     * @return array{value: int, growth: float|null}
     */
    private function cardStat(string $model, Carbon $since): array
    {
        return [
            'value' => $model::count(),
            'growth' => $this->growth(
                $model::where('created_at', '>=', $since)->count(),
                $model::whereBetween('created_at', [$since->copy()->subDays(self::TREND_DAYS), $since])->count(),
            ),
        ];
    }

    /**
     * Percentage change between two windows, or null when there is no baseline.
     */
    private function growth(int $current, int $previous): ?float
    {
        if ($previous === 0) {
            return null;
        }

        return round(($current - $previous) / $previous * 100, 1);
    }

    /**
     * Lead counts grouped by source for the overview donut, largest first.
     *
     * @return array<int, array{label: string, value: int}>
     */
    private function leadSegments(): array
    {
        $bySource = Lead::query()
            ->selectRaw('source, count(*) as total')
            ->groupBy('source')
            ->get()
            ->groupBy(fn (Lead $row): string => $row->source ?: 'Other')
            ->map(fn (Collection $rows): int => (int) $rows->sum('total'))
            ->sortDesc();

        $segments = $bySource->take(4);
        $rest = $bySource->skip(4)->sum();

        if ($rest > 0) {
            $segments->put('Other', ($segments->get('Other') ?? 0) + $rest);
        }

        return $segments
            ->map(fn (int $value, string $label): array => ['label' => $label, 'value' => $value])
            ->values()
            ->all();
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
