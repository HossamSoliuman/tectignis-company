<?php

namespace App\Services\Portal;

use App\Enums\Portal\ChecklistItemStatus;
use App\Enums\Portal\EligibilityStatus;
use App\Models\Portal\Tender;

/**
 * Derives "can we bid for this?" from the eligibility checklist (§9).
 *
 * The answer is computed, not typed: one unmet requirement makes the tender
 * Not Eligible, any pending answer keeps it Under Review, and only a full sweep
 * of Met / Not Applicable makes it Eligible. Management can still overrule the
 * verdict, but only by recording a reason — an override with no explanation is
 * how a bid gets submitted that should never have been.
 */
class EligibilityEvaluator
{
    /**
     * The verdict the checklist supports right now.
     */
    public function evaluate(Tender $tender): EligibilityStatus
    {
        $statuses = $tender->checklistItems()->pluck('status');

        if ($statuses->isEmpty()) {
            return EligibilityStatus::UnderReview;
        }

        if ($statuses->contains(ChecklistItemStatus::NotMet->value)) {
            return EligibilityStatus::NotEligible;
        }

        if ($statuses->contains(ChecklistItemStatus::Pending->value)) {
            return EligibilityStatus::UnderReview;
        }

        return EligibilityStatus::Eligible;
    }

    /**
     * Recompute and store the tender's eligibility, unless management has
     * overridden it — an override outranks the checklist until it is cleared.
     */
    public function refresh(Tender $tender): EligibilityStatus
    {
        if ($this->isOverridden($tender)) {
            return $tender->eligibility_status;
        }

        $status = $this->evaluate($tender);

        if ($tender->eligibility_status !== $status) {
            $tender->forceFill(['eligibility_status' => $status])->save();
        }

        return $status;
    }

    /**
     * Record a management override, which always carries its justification.
     */
    public function override(Tender $tender, EligibilityStatus $status, string $reason): void
    {
        $tender->update([
            'eligibility_status' => $status,
            'eligibility_override_reason' => $reason,
        ]);
    }

    /**
     * Drop the override and fall back to whatever the checklist says.
     */
    public function clearOverride(Tender $tender): EligibilityStatus
    {
        $tender->update(['eligibility_override_reason' => null]);

        return $this->refresh($tender);
    }

    public function isOverridden(Tender $tender): bool
    {
        return filled($tender->eligibility_override_reason);
    }

    /**
     * Checklist counts for the workspace header and the overview tab.
     *
     * @return array{total: int, met: int, not_met: int, pending: int, not_applicable: int}
     */
    public function counts(Tender $tender): array
    {
        $byStatus = $tender->checklistItems()
            ->selectRaw('status, count(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        return [
            'total' => (int) $byStatus->sum(),
            'met' => (int) $byStatus->get(ChecklistItemStatus::Met->value, 0),
            'not_met' => (int) $byStatus->get(ChecklistItemStatus::NotMet->value, 0),
            'pending' => (int) $byStatus->get(ChecklistItemStatus::Pending->value, 0),
            'not_applicable' => (int) $byStatus->get(ChecklistItemStatus::NotApplicable->value, 0),
        ];
    }
}
