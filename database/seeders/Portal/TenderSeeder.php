<?php

namespace Database\Seeders\Portal;

use App\Enums\Portal\ChecklistItemStatus;
use App\Enums\Portal\OemFollowupStatus;
use App\Enums\Portal\OemRequirementType;
use App\Enums\Portal\TenderDecision;
use App\Enums\Portal\TenderDocumentStatus;
use App\Enums\Portal\TenderPortalSource;
use App\Enums\Portal\TenderStage;
use App\Models\Portal\Employee;
use App\Models\Portal\OemFollowup;
use App\Models\Portal\Tender;
use App\Services\Portal\EligibilityEvaluator;
use App\Services\Portal\TenderCompletionService;
use App\Services\Portal\TenderTemplateService;
use Illuminate\Database\Seeder;

/**
 * Demo tenders so the workspace can be walked through end to end.
 *
 * Not part of PortalSeeder: that one bootstraps the real org structure, this
 * one is sample data. Run it explicitly:
 * `php artisan db:seed --class="Database\Seeders\Portal\TenderSeeder"`.
 *
 * Idempotent — keyed on tender_number, so re-running tops up rather than
 * duplicating.
 */
class TenderSeeder extends Seeder
{
    /**
     * @var list<array{tender_number: string, title: string, customer: string, portal: TenderPortalSource, days: int, stage: TenderStage, decision: TenderDecision, value: float}>
     */
    private const TENDERS = [
        [
            'tender_number' => 'GEM/2026/B/4471203',
            'title' => 'Supply and Installation of Network Security Appliances',
            'customer' => 'Department of Information Technology',
            'portal' => TenderPortalSource::Gem,
            'days' => 5,
            'stage' => TenderStage::DocumentationInProgress,
            'decision' => TenderDecision::Participate,
            'value' => 8_650_000,
        ],
        [
            'tender_number' => 'CPPP/2026/ITD/0917',
            'title' => 'Annual Maintenance Contract for Data Centre Infrastructure',
            'customer' => 'State Electricity Board',
            'portal' => TenderPortalSource::Cppp,
            'days' => 21,
            'stage' => TenderStage::UnderEvaluation,
            'decision' => TenderDecision::UnderReview,
            'value' => 3_200_000,
        ],
        [
            'tender_number' => 'GEM/2026/B/4398812',
            'title' => 'Procurement of Industrial Automation Controllers',
            'customer' => 'Municipal Water Works',
            'portal' => TenderPortalSource::Gem,
            'days' => 2,
            'stage' => TenderStage::TechnicalPreparation,
            'decision' => TenderDecision::Participate,
            'value' => 12_400_000,
        ],
    ];

    public function run(
        TenderTemplateService $templates,
        EligibilityEvaluator $eligibility,
        TenderCompletionService $completion,
    ): void {
        $owner = Employee::where('employee_code', 'EMP-0002')->first()
            ?? Employee::orderBy('id')->first();

        if ($owner === null) {
            $this->command?->warn('No employees found — run PortalSeeder first.');

            return;
        }

        foreach (self::TENDERS as $index => $data) {
            $tender = Tender::firstOrCreate(
                ['tender_number' => $data['tender_number']],
                [
                    'code' => Tender::nextCode(),
                    'title' => $data['title'],
                    'customer_organization' => $data['customer'],
                    'portal' => $data['portal'],
                    'published_at' => now()->subDays(10),
                    'pre_bid_at' => now()->subDays(3),
                    'submission_start_at' => now()->subDays(2),
                    'submission_deadline_at' => now()->addDays($data['days'])->setTime(15, 0),
                    'estimated_value' => $data['value'],
                    'emd_required' => true,
                    'emd_amount' => round($data['value'] * 0.02, 2),
                    'fee_required' => false,
                    'assigned_employee_id' => $owner->id,
                    'stage' => $data['stage'],
                    'decision' => $data['decision'],
                ],
            );

            $templates->apply($tender, $owner);

            $this->answerSomeChecklist($tender, $index);
            $this->settleSomeDocuments($tender, $index);
            $this->raiseOemChase($tender, $owner, $index);

            $eligibility->refresh($tender);
            $completion->refresh($tender);
        }
    }

    /**
     * Leave a realistic mix of answered and pending requirements — a checklist
     * that is entirely one status teaches nobody anything about the screen.
     */
    private function answerSomeChecklist(Tender $tender, int $index): void
    {
        $tender->checklistItems()
            ->where('status', ChecklistItemStatus::Pending)
            ->orderBy('sort_order')
            ->limit(6 + $index)
            ->update(['status' => ChecklistItemStatus::Met]);
    }

    private function settleSomeDocuments(Tender $tender, int $index): void
    {
        $tender->documents()
            ->where('status', TenderDocumentStatus::Pending)
            ->orderBy('sort_order')
            ->limit(5 + $index * 3)
            ->update(['status' => TenderDocumentStatus::Ready]);
    }

    private function raiseOemChase(Tender $tender, Employee $owner, int $index): void
    {
        if ($tender->oemFollowups()->exists()) {
            return;
        }

        OemFollowup::create([
            'tender_id' => $tender->id,
            'oem_name' => ['Cisco Systems', 'Siemens', 'Schneider Electric'][$index] ?? 'Generic OEM',
            'requirement_type' => OemRequirementType::Maf,
            'product' => 'Bid line item',
            'requested_by_id' => $owner->id,
            'requested_on' => now()->subDays(4),
            'required_by' => $tender->submission_deadline_at?->copy()->subDays(3),
            'contact_person' => 'Channel Manager',
            'contact_channel' => 'partners@example.com',
            'status' => OemFollowupStatus::Requested,
            // One is deliberately overdue, so the chase queue has something in it.
            'next_followup_at' => $index === 0 ? now()->subDay() : now()->addDays(2),
        ]);
    }
}
