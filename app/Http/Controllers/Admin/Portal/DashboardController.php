<?php

namespace App\Http\Controllers\Admin\Portal;

use App\Http\Controllers\Controller;
use App\Models\Portal\ActivityLog;
use App\Models\Portal\DailyWorkUpdate;
use App\Models\Portal\Employee;
use App\Models\Portal\OemFollowup;
use App\Models\Portal\Tender;
use App\Models\Portal\TenderDocument;
use App\Services\Portal\OverdueService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

/**
 * Management's live picture of the day (§4).
 *
 * Managers and directors see the whole business; an employee sees the same
 * layout scoped to their own work, so there is one dashboard to maintain.
 */
class DashboardController extends Controller
{
    public function __invoke(Request $request, OverdueService $overdue): View
    {
        $user = $request->user();
        $manages = (bool) $user->portalRole()?->managesOthers();
        $scopeId = $manages ? null : $user->portalEmployee()->id;

        return view('admin.portal.dashboard', [
            'counts' => $overdue->counts($scopeId),
            'overdueTasks' => $overdue->overdueTasks($scopeId, 10),
            'dueSoonTasks' => $overdue->dueSoonTasks($scopeId),
            'todaysWork' => $this->todaysWork($manages, $scopeId),
            'employeeStatus' => $manages ? $this->employeeWorkStatus() : collect(),
            'recentActivity' => $manages
                ? ActivityLog::with('user:id,name')->latest('created_at')->limit(12)->get()
                : new Collection,
            'manages' => $manages,
            ...$this->tenderPicture($scopeId),
        ]);
    }

    /**
     * The bid side of the day (§4, Phase 2): what is live, what closes soon,
     * what paperwork is missing and which OEM nobody has chased.
     *
     * @return array<string, mixed>
     */
    private function tenderPicture(?int $scopeId): array
    {
        // Same visibility rule as the tenders index, so the dashboard count and
        // the list it links to can never disagree.
        $visible = fn (Builder $query): Builder => $query->when(
            $scopeId !== null,
            fn (Builder $builder) => $builder->where(function (Builder $inner) use ($scopeId): void {
                $inner->forOwner($scopeId)
                    ->orWhereHas('tasks', fn (Builder $tasks) => $tasks->where('assigned_to_id', $scopeId));
            }),
        );

        $closingSoon = $visible(Tender::query())
            ->closingSoon()
            ->with('owner:id,name')
            ->orderBy('submission_deadline_at')
            ->limit(8)
            ->get();

        $visibleTenderIds = $visible(Tender::query())->select('id');

        return [
            'tenderCounts' => [
                'active' => $visible(Tender::query())->active()->count(),
                'closing_soon' => $visible(Tender::query())->closingSoon()->count(),
                'deadline_passed' => $visible(Tender::query())->deadlinePassed()->count(),
                'document_gaps' => TenderDocument::query()
                    ->missingMandatory()
                    ->whereIn('tender_id', $visibleTenderIds)
                    ->count(),
                'oem_due' => OemFollowup::query()
                    ->due()
                    ->where(fn (Builder $query) => $query->whereNull('tender_id')->orWhereIn('tender_id', $visibleTenderIds))
                    ->count(),
            ],
            'closingSoonTenders' => $closingSoon,
            'dueOemFollowups' => OemFollowup::query()
                ->due()
                ->where(fn (Builder $query) => $query->whereNull('tender_id')->orWhereIn('tender_id', $visibleTenderIds))
                ->with('tender:id,code,title')
                ->orderByRaw('next_followup_at is null, next_followup_at')
                ->limit(8)
                ->get(),
        ];
    }

    /**
     * Everything logged for today, company-wide or just the viewer's own.
     *
     * @return Collection<int, DailyWorkUpdate>
     */
    private function todaysWork(bool $manages, ?int $employeeId): Collection
    {
        return DailyWorkUpdate::query()
            ->with('employee:id,name')
            ->onDate(now())
            ->when(! $manages, fn ($query) => $query->where('employee_id', $employeeId))
            ->latest('created_at')
            ->limit(15)
            ->get();
    }

    /**
     * Who has reported in today and who has not — the "Employee Work Status"
     * section of §4, and the fastest way to spot a silent day.
     *
     * @return Collection<int, Employee>
     */
    private function employeeWorkStatus(): Collection
    {
        return Employee::query()
            ->active()
            ->with('department:id,name')
            ->withCount([
                'dailyWorkUpdates as todays_updates_count' => fn ($query) => $query->whereDate('work_date', now()),
                'assignedTasks as open_tasks_count' => fn ($query) => $query->open(),
                'assignedTasks as overdue_tasks_count' => fn ($query) => $query->overdue(),
            ])
            ->orderBy('name')
            ->limit(20)
            ->get();
    }
}
