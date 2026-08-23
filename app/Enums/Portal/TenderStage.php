<?php

namespace App\Enums\Portal;

/**
 * The tender lifecycle (§8), ordered from discovery to outcome.
 *
 * Stage is the single answer to "where is this tender right now" that the index
 * screen, the workspace header and the dashboard all read.
 */
enum TenderStage: string
{
    case Identified = 'identified';
    case UnderEvaluation = 'under_evaluation';
    case DocumentationInProgress = 'documentation_in_progress';
    case TechnicalPreparation = 'technical_preparation';
    case CommercialPreparation = 'commercial_preparation';
    case ReadyForSubmission = 'ready_for_submission';
    case Submitted = 'submitted';
    case UnderTechnicalEvaluation = 'under_technical_evaluation';
    case ResultAwaited = 'result_awaited';
    case Closed = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::Identified => 'Identified',
            self::UnderEvaluation => 'Under Evaluation',
            self::DocumentationInProgress => 'Documentation in Progress',
            self::TechnicalPreparation => 'Technical Preparation',
            self::CommercialPreparation => 'Commercial Preparation',
            self::ReadyForSubmission => 'Ready for Submission',
            self::Submitted => 'Submitted',
            self::UnderTechnicalEvaluation => 'Under Technical Evaluation',
            self::ResultAwaited => 'Result Awaited',
            self::Closed => 'Closed',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::Identified, self::Closed => 'slate',
            self::UnderEvaluation, self::ResultAwaited => 'amber',
            self::DocumentationInProgress, self::TechnicalPreparation, self::CommercialPreparation => 'sky',
            self::ReadyForSubmission => 'violet',
            self::Submitted, self::UnderTechnicalEvaluation => 'emerald',
        };
    }

    /**
     * Position in the pipeline, 1-based — used to render progress and to reject
     * a stage jump that skips the work in between.
     */
    public function step(): int
    {
        return array_search($this, self::cases(), true) + 1;
    }

    /**
     * Stages after which the bid is out of our hands: deadlines stop mattering
     * and the tender leaves the "closing soon" alerts.
     */
    public function isSubmittedOrLater(): bool
    {
        return $this->step() >= self::Submitted->step();
    }

    public function isClosed(): bool
    {
        return $this === self::Closed;
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $stage): array => [$stage->value => $stage->label()])
            ->all();
    }
}
