<?php

namespace App\Services\Portal;

use App\Enums\Portal\ChecklistItemStatus;
use App\Enums\Portal\ClarificationStatus;
use App\Enums\Portal\OemFollowupStatus;
use App\Enums\Portal\TaskStatus;
use App\Enums\Portal\TenderDocumentStatus;
use App\Models\Portal\Tender;

/**
 * "How ready is this bid?" answered as one number and one summary block (§28).
 *
 * The percentage is weighted rather than a flat row count, because a missing
 * mandatory document costs far more than an unticked nicety. It is cached on
 * `portal_tenders.completion_percent` so index screens stay cheap, and
 * recomputed whenever a child record moves.
 */
class TenderCompletionService
{
    /**
     * Weight each area carries in the readiness percentage. Documents dominate
     * because a bid stands or falls on paperwork.
     *
     * @var array<string, int>
     */
    private const WEIGHTS = [
        'eligibility' => 20,
        'documents' => 35,
        'tasks' => 25,
        'oem' => 10,
        'submission' => 10,
    ];

    /**
     * Recompute and persist the cached percentage.
     */
    public function refresh(Tender $tender): int
    {
        $percent = $this->percent($tender);

        if ($tender->completion_percent !== $percent) {
            // forceFill + save rather than update(): readiness is derived data,
            // and a recompute is not an edit anybody made.
            $tender->forceFill(['completion_percent' => $percent])->save();
        }

        return $percent;
    }

    /**
     * Weighted readiness across every area that has anything to complete.
     * Areas with nothing in them are dropped, so an empty OEM list cannot cap
     * a tender at 90%.
     */
    public function percent(Tender $tender): int
    {
        $ratios = array_filter(
            $this->ratios($tender),
            fn (?float $ratio): bool => $ratio !== null,
        );

        if ($ratios === []) {
            return 0;
        }

        $weighted = 0.0;
        $weight = 0;

        foreach ($ratios as $area => $ratio) {
            $weighted += $ratio * self::WEIGHTS[$area];
            $weight += self::WEIGHTS[$area];
        }

        return (int) round($weighted / $weight * 100);
    }

    /**
     * Per-area completion between 0 and 1, or null when the area is empty.
     *
     * @return array<string, float|null>
     */
    public function ratios(Tender $tender): array
    {
        $summary = $this->summary($tender);

        return [
            'eligibility' => $this->ratio($summary['eligibility']['answered'], $summary['eligibility']['total']),
            'documents' => $this->ratio($summary['documents']['settled'], $summary['documents']['total']),
            'tasks' => $this->ratio($summary['tasks']['completed'], $summary['tasks']['total']),
            'oem' => $this->ratio($summary['oem']['received'], $summary['oem']['total']),
            'submission' => $this->ratio($summary['submission']['checked'], $summary['submission']['total']),
        ];
    }

    /**
     * The §28 overview block: what is done, what is outstanding, per area.
     *
     * @return array{
     *     eligibility: array{total: int, met: int, pending: int, not_met: int, answered: int},
     *     documents: array{total: int, settled: int, pending: int, mandatory: int, mandatory_missing: int, expired: int},
     *     tasks: array{total: int, completed: int, open: int, overdue: int},
     *     oem: array{total: int, received: int, pending: int, overdue: int},
     *     clarifications: array{total: int, pending: int, answered: int},
     *     submission: array{total: int, checked: int, remaining: int}
     * }
     */
    public function summary(Tender $tender): array
    {
        $documents = $tender->documents()->get(['status', 'is_required', 'expires_on']);
        $checklist = $tender->checklistItems()->get(['status']);
        $oem = $tender->oemFollowups()->get(['status', 'required_by']);
        $clarifications = $tender->clarifications()->get(['status']);
        $checks = $tender->submissionChecks()->get(['is_checked']);
        $tasks = $tender->tasks()->get(['status', 'due_date']);

        $mandatory = $documents->where('is_required', true);

        return [
            'eligibility' => [
                'total' => $checklist->count(),
                'met' => $checklist->where('status', ChecklistItemStatus::Met)->count(),
                'pending' => $checklist->where('status', ChecklistItemStatus::Pending)->count(),
                'not_met' => $checklist->where('status', ChecklistItemStatus::NotMet)->count(),
                'answered' => $checklist->filter(fn ($item): bool => ! $item->status->isUnanswered())->count(),
            ],
            'documents' => [
                'total' => $documents->count(),
                'settled' => $documents->filter(fn ($doc): bool => $doc->status->isSettled())->count(),
                'pending' => $documents->reject(fn ($doc): bool => $doc->status->isSettled())->count(),
                'mandatory' => $mandatory->count(),
                'mandatory_missing' => $mandatory->reject(fn ($doc): bool => $doc->status->isSettled())->count(),
                'expired' => $documents->filter(fn ($doc): bool => $doc->isExpired())->count(),
            ],
            'tasks' => [
                'total' => $tasks->count(),
                'completed' => $tasks->where('status', TaskStatus::Completed)->count(),
                'open' => $tasks->reject(fn ($task): bool => $task->status->isClosed())->count(),
                'overdue' => $tasks->filter(fn ($task): bool => $task->isOverdue())->count(),
            ],
            'oem' => [
                'total' => $oem->count(),
                'received' => $oem->where('status', OemFollowupStatus::Received)->count(),
                'pending' => $oem->reject(fn ($item): bool => $item->status->isClosed())->count(),
                'overdue' => $oem->filter(fn ($item): bool => $item->isOverdue())->count(),
            ],
            'clarifications' => [
                'total' => $clarifications->count(),
                'pending' => $clarifications->where('status', ClarificationStatus::Pending)->count(),
                'answered' => $clarifications->where('status', ClarificationStatus::Answered)->count(),
            ],
            'submission' => [
                'total' => $checks->count(),
                'checked' => $checks->where('is_checked', true)->count(),
                'remaining' => $checks->where('is_checked', false)->count(),
            ],
        ];
    }

    /**
     * Mandatory documents still outstanding — the gate that blocks the final
     * submission checklist (§14).
     */
    public function missingMandatoryDocuments(Tender $tender): int
    {
        return $tender->documents()
            ->where('is_required', true)
            ->whereNotIn('status', array_column(TenderDocumentStatus::settled(), 'value'))
            ->count();
    }

    public function isReadyToSubmit(Tender $tender): bool
    {
        return $this->missingMandatoryDocuments($tender) === 0;
    }

    private function ratio(int $done, int $total): ?float
    {
        return $total === 0 ? null : $done / $total;
    }
}
