<?php

namespace App\Observers\Portal;

use App\Models\Portal\Tender;
use App\Services\Portal\EligibilityEvaluator;
use App\Services\Portal\TenderCompletionService;
use Illuminate\Database\Eloquent\Model;

/**
 * Keeps a tender's derived state honest when its children move.
 *
 * Readiness (§28) and eligibility (§9) are computed from checklist items,
 * documents, tasks and OEM requests. Recomputing here — rather than in each
 * controller that happens to touch one — means a change made by a queued job, a
 * console command or a seeder updates the tender exactly like a UI edit does.
 */
class TenderProgressObserver
{
    public function __construct(
        private readonly TenderCompletionService $completion,
        private readonly EligibilityEvaluator $eligibility,
    ) {}

    public function saved(Model $model): void
    {
        $this->sync($model);
    }

    public function deleted(Model $model): void
    {
        $this->sync($model);
    }

    private function sync(Model $model): void
    {
        $tender = $this->tenderFor($model);

        if ($tender === null) {
            return;
        }

        $this->eligibility->refresh($tender);
        $this->completion->refresh($tender);
    }

    /**
     * The tender a child record belongs to. Tasks reach it through the `related`
     * morph, everything else through a direct relation; records with no tender
     * (a standalone OEM request, a task on a sales lead) simply have nothing to
     * recompute.
     */
    private function tenderFor(Model $model): ?Tender
    {
        $owner = method_exists($model, 'tender')
            ? $model->tender
            : ($model->getAttribute('related_type') === (new Tender)->getMorphClass()
                ? Tender::find($model->getAttribute('related_id'))
                : null);

        return $owner instanceof Tender ? $owner : null;
    }
}
