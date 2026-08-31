<?php

namespace App\Http\Controllers\Admin\Portal;

use App\Http\Controllers\Admin\Portal\Concerns\ResolvesTenderWorkspace;
use App\Http\Controllers\Controller;
use App\Models\Portal\ActivityLog;
use App\Models\Portal\OemFollowup;
use App\Models\Portal\Task;
use App\Models\Portal\Tender;
use App\Models\Portal\TenderChecklistItem;
use App\Models\Portal\TenderClarification;
use App\Models\Portal\TenderDocument;
use App\Models\Portal\TenderResult;
use App\Models\Portal\TenderSubmissionCheck;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * The audit trail for one bid (§20, §27).
 *
 * The workspace writes to eight tables, so "what happened on this tender?" has
 * to gather the tender's own entries together with those of every child record.
 * Read-only — nothing on this screen can alter what it shows.
 */
class TenderActivityController extends Controller
{
    use ResolvesTenderWorkspace;

    /**
     * Child models whose history belongs to the tender's story.
     *
     * @var array<int, class-string<Model>>
     */
    private const CHILD_MODELS = [
        TenderChecklistItem::class,
        TenderDocument::class,
        OemFollowup::class,
        TenderClarification::class,
        TenderSubmissionCheck::class,
        TenderResult::class,
        Task::class,
    ];

    public function index(Tender $tender): View
    {
        $logs = ActivityLog::query()
            ->with('user:id,name')
            ->where(function (Builder $query) use ($tender): void {
                $query->forSubject($tender);

                foreach ($this->childKeys($tender) as $type => $ids) {
                    if ($ids !== []) {
                        $query->orWhere(fn (Builder $inner) => $inner
                            ->where('subject_type', $type)
                            ->whereIn('subject_id', $ids));
                    }
                }
            })
            ->latest('id')
            ->paginate(40)
            ->withQueryString();

        return view('admin.portal.tenders.tabs.activity', $this->viewWorkspace($tender, 'activity', [
            'logs' => $logs,
        ]));
    }

    /**
     * Morph type => ids of every record hanging off this tender.
     *
     * @return array<string, array<int, int>>
     */
    private function childKeys(Tender $tender): array
    {
        $relations = [
            TenderChecklistItem::class => 'checklistItems',
            TenderDocument::class => 'documents',
            OemFollowup::class => 'oemFollowups',
            TenderClarification::class => 'clarifications',
            TenderSubmissionCheck::class => 'submissionChecks',
            TenderResult::class => 'result',
            Task::class => 'tasks',
        ];

        $keys = [];

        foreach (self::CHILD_MODELS as $model) {
            $keys[(new $model)->getMorphClass()] = $tender->{$relations[$model]}()->pluck('id')->all();
        }

        return $keys;
    }
}
