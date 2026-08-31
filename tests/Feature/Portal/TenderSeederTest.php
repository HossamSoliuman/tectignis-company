<?php

use App\Enums\Portal\ChecklistItemStatus;
use App\Enums\Portal\EligibilityStatus;
use App\Models\Portal\Employee;
use App\Models\Portal\OemFollowup;
use App\Models\Portal\Tender;
use Database\Seeders\Portal\TenderSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('builds three demo tenders with a workspace that can be walked through', function () {
    Employee::factory()->create(['employee_code' => 'EMP-0002']);

    $this->seed(TenderSeeder::class);

    expect(Tender::count())->toBe(3);

    $tender = Tender::firstWhere('tender_number', 'GEM/2026/B/4471203');

    expect($tender->checklistItems()->count())->toBeGreaterThan(0)
        ->and($tender->checklistItems()->where('status', ChecklistItemStatus::Met)->count())->toBe(6)
        ->and($tender->documents()->count())->toBeGreaterThan(0)
        ->and($tender->tasks()->count())->toBeGreaterThan(0)
        ->and($tender->oemFollowups()->count())->toBe(1)
        ->and($tender->completion_percent)->toBeGreaterThan(0);

    // The demo data deliberately stops short of a finished checklist, so the
    // eligibility verdict is the honest one for half-answered requirements.
    expect($tender->checklistItems()->where('status', ChecklistItemStatus::Pending)->count())->toBeGreaterThan(0)
        ->and($tender->eligibility_status)->toBe(EligibilityStatus::UnderReview);
});

it('assigns every demo tender to the seeded owner', function () {
    $owner = Employee::factory()->create(['employee_code' => 'EMP-0002']);
    Employee::factory()->create();

    $this->seed(TenderSeeder::class);

    expect(Tender::where('assigned_employee_id', $owner->id)->count())->toBe(3);
});

it('leaves one overdue chase in the queue so the followup screen has content', function () {
    Employee::factory()->create(['employee_code' => 'EMP-0002']);

    $this->seed(TenderSeeder::class);

    expect(OemFollowup::where('next_followup_at', '<', now())->count())->toBe(1);
});

it('tops up rather than duplicating when run twice', function () {
    Employee::factory()->create(['employee_code' => 'EMP-0002']);

    $this->seed(TenderSeeder::class);
    $this->seed(TenderSeeder::class);

    expect(Tender::count())->toBe(3)
        ->and(OemFollowup::count())->toBe(3);
});

it('backs out quietly when the org structure has not been seeded yet', function () {
    $this->seed(TenderSeeder::class);

    expect(Tender::count())->toBe(0);
});
