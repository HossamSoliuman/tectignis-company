<?php

namespace App\Services\Portal;

use App\Enums\Portal\TaskPriority;
use App\Enums\Portal\TaskStatus;
use App\Enums\Portal\TenderDocumentCategory;
use App\Models\Portal\Employee;
use App\Models\Portal\Tender;
use App\Models\Portal\TenderChecklistItem;
use App\Models\Portal\TenderDocument;
use App\Models\Portal\TenderSubmissionCheck;
use App\Models\Portal\Task;
use Illuminate\Support\Carbon;

/**
 * Turns a newly created tender into a workspace that already knows what has to
 * happen (§9, §10, §12, §14).
 *
 * Every bid repeats the same eligibility questions, the same document set and
 * the same twelve steps. Seeding them means nobody starts from a blank page and
 * nothing standard is forgotten under deadline pressure. Templates live here as
 * constants; making them editable from the UI is a later phase.
 */
class TenderTemplateService
{
    /**
     * The §9 eligibility requirements, in the order they get answered.
     *
     * @var list<array{requirement: string, category: string}>
     */
    private const ELIGIBILITY_ITEMS = [
        ['requirement' => 'Company registration / incorporation valid', 'category' => 'Company'],
        ['requirement' => 'GST registration active', 'category' => 'Company'],
        ['requirement' => 'PAN and TAN available', 'category' => 'Company'],
        ['requirement' => 'Average annual turnover meets the tender threshold', 'category' => 'Financial'],
        ['requirement' => 'Audited financial statements for the required years', 'category' => 'Financial'],
        ['requirement' => 'Net worth positive as per the tender condition', 'category' => 'Financial'],
        ['requirement' => 'Similar work / past performance experience met', 'category' => 'Experience'],
        ['requirement' => 'Work completion certificates available', 'category' => 'Experience'],
        ['requirement' => 'Required technical certifications (ISO / OEM / statutory)', 'category' => 'Technical'],
        ['requirement' => 'OEM authorisation obtainable for the quoted make', 'category' => 'Technical'],
        ['requirement' => 'EMD and tender fee affordable and arrangeable in time', 'category' => 'Commercial'],
        ['requirement' => 'No blacklisting / debarment by any authority', 'category' => 'Legal'],
    ];

    /**
     * The §10 document set, grouped by the seven bid categories.
     *
     * @var list<array{name: string, category: TenderDocumentCategory, required: bool}>
     */
    private const DOCUMENTS = [
        ['name' => 'Certificate of Incorporation / Registration', 'category' => TenderDocumentCategory::Company, 'required' => true],
        ['name' => 'GST Registration Certificate', 'category' => TenderDocumentCategory::Company, 'required' => true],
        ['name' => 'PAN Card', 'category' => TenderDocumentCategory::Company, 'required' => true],
        ['name' => 'MSME / Udyam Certificate', 'category' => TenderDocumentCategory::Company, 'required' => false],
        ['name' => 'Audited Balance Sheet & P&L (3 years)', 'category' => TenderDocumentCategory::Financial, 'required' => true],
        ['name' => 'Income Tax Returns (3 years)', 'category' => TenderDocumentCategory::Financial, 'required' => true],
        ['name' => 'Turnover / Net Worth Certificate (CA attested)', 'category' => TenderDocumentCategory::Financial, 'required' => true],
        ['name' => 'Bank Solvency Certificate', 'category' => TenderDocumentCategory::Financial, 'required' => false],
        ['name' => 'Technical Compliance Sheet', 'category' => TenderDocumentCategory::Technical, 'required' => true],
        ['name' => 'Product Datasheets / Brochures', 'category' => TenderDocumentCategory::Technical, 'required' => true],
        ['name' => 'Past Work Orders & Completion Certificates', 'category' => TenderDocumentCategory::Technical, 'required' => true],
        ['name' => 'Manufacturer Authorisation Form (MAF)', 'category' => TenderDocumentCategory::Oem, 'required' => true],
        ['name' => 'OEM Warranty / Support Undertaking', 'category' => TenderDocumentCategory::Oem, 'required' => false],
        ['name' => 'Bid Declaration / Undertaking', 'category' => TenderDocumentCategory::BidLegal, 'required' => true],
        ['name' => 'Non-Blacklisting Affidavit', 'category' => TenderDocumentCategory::BidLegal, 'required' => true],
        ['name' => 'Power of Attorney / Authorised Signatory Letter', 'category' => TenderDocumentCategory::BidLegal, 'required' => true],
        ['name' => 'Priced BOQ / Price Schedule', 'category' => TenderDocumentCategory::Commercial, 'required' => true],
        ['name' => 'EMD Proof / Exemption Certificate', 'category' => TenderDocumentCategory::Commercial, 'required' => true],
        ['name' => 'Tender Fee Payment Proof', 'category' => TenderDocumentCategory::Commercial, 'required' => false],
        ['name' => 'Final Bid Package (as uploaded)', 'category' => TenderDocumentCategory::Submission, 'required' => true],
        ['name' => 'Submission Acknowledgement / Bid Receipt', 'category' => TenderDocumentCategory::Submission, 'required' => true],
    ];

    /**
     * The §12 standard execution steps, with how long before the deadline each
     * one should be finished and who normally owns it.
     *
     * @var list<array{title: string, days_before: int, priority: TaskPriority}>
     */
    private const TASKS = [
        ['title' => 'Study the tender document and note key conditions', 'days_before' => 14, 'priority' => TaskPriority::High],
        ['title' => 'Confirm eligibility against the checklist', 'days_before' => 13, 'priority' => TaskPriority::High],
        ['title' => 'Take the participate / do not participate decision', 'days_before' => 12, 'priority' => TaskPriority::Critical],
        ['title' => 'Collect company and financial documents', 'days_before' => 10, 'priority' => TaskPriority::Medium],
        ['title' => 'Request OEM authorisation (MAF) and quotations', 'days_before' => 10, 'priority' => TaskPriority::Critical],
        ['title' => 'Raise pre-bid clarifications', 'days_before' => 9, 'priority' => TaskPriority::Medium],
        ['title' => 'Prepare the technical compliance sheet', 'days_before' => 7, 'priority' => TaskPriority::High],
        ['title' => 'Prepare the commercial bid and BOQ pricing', 'days_before' => 5, 'priority' => TaskPriority::High],
        ['title' => 'Arrange EMD and tender fee', 'days_before' => 4, 'priority' => TaskPriority::Critical],
        ['title' => 'Assemble and review the complete bid package', 'days_before' => 2, 'priority' => TaskPriority::High],
        ['title' => 'Upload the bid and complete submission', 'days_before' => 1, 'priority' => TaskPriority::Critical],
        ['title' => 'File the submission acknowledgement and record the result', 'days_before' => 0, 'priority' => TaskPriority::Medium],
    ];

    /**
     * The §14 final checks, run in the last hours before upload.
     *
     * @var list<string>
     */
    private const SUBMISSION_CHECKS = [
        'All mandatory documents uploaded in the required format',
        'Every document signed and stamped where required',
        'Technical bid contains no pricing information',
        'Commercial bid / BOQ figures cross-checked',
        'EMD paid and proof attached (or exemption enclosed)',
        'Tender fee paid and proof attached',
        'MAF and OEM documents valid on the submission date',
        'Certificates within their validity period',
        'Compliance sheet matches the tender specification',
        'Pre-bid clarification responses incorporated',
        'Bid submitted in the correct envelope structure',
        'Digital signature certificate working and applied',
        'File sizes and formats within the portal limits',
        'Final package reviewed by the tender owner',
        'Submission acknowledgement downloaded and filed',
    ];

    /**
     * Populate a brand new tender with everything the team should start from.
     *
     * @return array{checklist: int, documents: int, submission_checks: int, tasks: int}
     */
    public function apply(Tender $tender, ?Employee $creator = null, bool $withTasks = true): array
    {
        return [
            'checklist' => $this->applyEligibilityChecklist($tender),
            'documents' => $this->applyDocumentChecklist($tender),
            'submission_checks' => $this->applySubmissionChecklist($tender),
            'tasks' => $withTasks ? $this->applyTaskTemplate($tender, $creator) : 0,
        ];
    }

    /**
     * @return int rows created
     */
    public function applyEligibilityChecklist(Tender $tender): int
    {
        if ($tender->checklistItems()->exists()) {
            return 0;
        }

        foreach (self::ELIGIBILITY_ITEMS as $index => $item) {
            TenderChecklistItem::create([
                'tender_id' => $tender->id,
                'requirement' => $item['requirement'],
                'category' => $item['category'],
                'sort_order' => $index,
            ]);
        }

        return count(self::ELIGIBILITY_ITEMS);
    }

    /**
     * @return int rows created
     */
    public function applyDocumentChecklist(Tender $tender): int
    {
        if ($tender->documents()->exists()) {
            return 0;
        }

        foreach (self::DOCUMENTS as $index => $document) {
            TenderDocument::create([
                'tender_id' => $tender->id,
                'name' => $document['name'],
                'category' => $document['category'],
                'is_required' => $document['required'],
                'required_by' => $tender->submission_deadline_at?->copy()->subDays(2),
                'sort_order' => $index,
            ]);
        }

        return count(self::DOCUMENTS);
    }

    /**
     * @return int rows created
     */
    public function applySubmissionChecklist(Tender $tender): int
    {
        if ($tender->submissionChecks()->exists()) {
            return 0;
        }

        foreach (self::SUBMISSION_CHECKS as $index => $label) {
            TenderSubmissionCheck::create([
                'tender_id' => $tender->id,
                'label' => $label,
                'sort_order' => $index,
            ]);
        }

        return count(self::SUBMISSION_CHECKS);
    }

    /**
     * Create the standard task list, back-scheduled from the submission
     * deadline so every step already carries a realistic date.
     *
     * @return int tasks created
     */
    public function applyTaskTemplate(Tender $tender, ?Employee $creator = null): int
    {
        if ($tender->tasks()->exists()) {
            return 0;
        }

        $owner = $tender->assigned_employee_id ?? $creator?->id;

        foreach (self::TASKS as $template) {
            Task::create([
                'code' => Task::nextCode(),
                'title' => $template['title'],
                'description' => 'Standard tender step for '.$tender->tender_number.'.',
                'assigned_to_id' => $owner,
                'created_by_id' => $creator?->id,
                'priority' => $template['priority'],
                'status' => TaskStatus::NotStarted,
                'due_date' => $this->dueDateFor($tender, $template['days_before']),
                'related_type' => $tender->getMorphClass(),
                'related_id' => $tender->id,
            ]);
        }

        return count(self::TASKS);
    }

    /**
     * A template step is due the given number of days before submission closes.
     * A date already in the past is pulled forward to now rather than seeding a
     * task that is born overdue.
     */
    private function dueDateFor(Tender $tender, int $daysBefore): ?Carbon
    {
        if ($tender->submission_deadline_at === null) {
            return null;
        }

        $due = $tender->submission_deadline_at->copy()->subDays($daysBefore);

        return $due->isPast() ? now()->addDay() : $due;
    }
}
