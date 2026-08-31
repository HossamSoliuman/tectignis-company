<?php

use App\Enums\Portal\ChecklistItemStatus;
use App\Enums\Portal\EligibilityStatus;
use App\Enums\Portal\PortalRole;
use App\Models\Portal\Employee;
use App\Models\Portal\Tender;
use App\Models\Portal\TenderChecklistItem;
use App\Models\User;
use App\Services\Portal\EligibilityEvaluator;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('holds a tender under review while any requirement is unanswered', function () {
    $tender = Tender::factory()->create();
    TenderChecklistItem::factory()->met()->create(['tender_id' => $tender->id]);
    TenderChecklistItem::factory()->create(['tender_id' => $tender->id]);

    expect(app(EligibilityEvaluator::class)->evaluate($tender))->toBe(EligibilityStatus::UnderReview);
});

it('marks a tender not eligible the moment one requirement is unmet', function () {
    $tender = Tender::factory()->create();
    TenderChecklistItem::factory()->met()->count(5)->create(['tender_id' => $tender->id]);
    TenderChecklistItem::factory()->notMet()->create(['tender_id' => $tender->id]);

    expect(app(EligibilityEvaluator::class)->evaluate($tender))->toBe(EligibilityStatus::NotEligible);
});

it('treats not-applicable as answered', function () {
    $tender = Tender::factory()->create();
    TenderChecklistItem::factory()->met()->count(2)->create(['tender_id' => $tender->id]);
    TenderChecklistItem::factory()->notApplicable()->create(['tender_id' => $tender->id]);

    expect(app(EligibilityEvaluator::class)->evaluate($tender))->toBe(EligibilityStatus::Eligible);
});

it('recomputes the stored verdict when a requirement is answered', function () {
    [$director] = portalUser();
    $tender = Tender::factory()->create(['eligibility_status' => EligibilityStatus::UnderReview]);
    $item = TenderChecklistItem::factory()->create(['tender_id' => $tender->id]);

    $this->actingAs($director)->put(route('admin.portal.tenders.eligibility.update', [$tender, $item]), [
        'status' => ChecklistItemStatus::Met->value,
    ])->assertRedirect();

    expect($tender->refresh()->eligibility_status)->toBe(EligibilityStatus::Eligible);
});

it('refuses an override without a recorded reason', function () {
    [$director] = portalUser();
    $tender = Tender::factory()->create();

    $this->actingAs($director)->put(route('admin.portal.tenders.eligibility.override', $tender), [
        'eligibility_status' => EligibilityStatus::Eligible->value,
        'eligibility_override_reason' => 'ok',
    ])->assertSessionHasErrors('eligibility_override_reason');

    expect($tender->refresh()->eligibility_override_reason)->toBeNull();
});

it('lets an override outrank the checklist until it is cleared', function () {
    [$director] = portalUser();
    $tender = Tender::factory()->create();
    TenderChecklistItem::factory()->notMet()->create(['tender_id' => $tender->id]);

    $this->actingAs($director)->put(route('admin.portal.tenders.eligibility.override', $tender), [
        'eligibility_status' => EligibilityStatus::Eligible->value,
        'eligibility_override_reason' => 'Turnover shortfall waived by the authority in writing.',
    ])->assertRedirect();

    // Another child change must not quietly undo a management decision.
    app(EligibilityEvaluator::class)->refresh($tender->refresh());

    expect($tender->refresh()->eligibility_status)->toBe(EligibilityStatus::Eligible);

    $this->actingAs($director)->delete(route('admin.portal.tenders.eligibility.override.clear', $tender))->assertRedirect();

    expect($tender->refresh()->eligibility_status)->toBe(EligibilityStatus::NotEligible)
        ->and($tender->eligibility_override_reason)->toBeNull();
});

it('keeps the override away from an ordinary employee', function () {
    $user = User::factory()->portal(PortalRole::Employee)->create();
    $employee = Employee::factory()->create(['user_id' => $user->id]);
    $tender = Tender::factory()->create(['assigned_employee_id' => $employee->id]);

    $this->actingAs($user)->put(route('admin.portal.tenders.eligibility.override', $tender), [
        'eligibility_status' => EligibilityStatus::Eligible->value,
        'eligibility_override_reason' => 'Because I would like to bid for it.',
    ])->assertForbidden();
});
