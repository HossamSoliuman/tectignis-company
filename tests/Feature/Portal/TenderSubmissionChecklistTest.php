<?php

use App\Enums\Portal\TenderDocumentStatus;
use App\Enums\Portal\TenderStage;
use App\Models\Portal\Tender;
use App\Models\Portal\TenderDocument;
use App\Models\Portal\TenderSubmissionCheck;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('records who ticked a submission check and when', function () {
    [$director, $employee] = portalUser();
    $tender = Tender::factory()->create();
    $check = TenderSubmissionCheck::create(['tender_id' => $tender->id, 'label' => 'Price sheet in the commercial envelope']);

    $this->actingAs($director)->put(route('admin.portal.tenders.submission.update', [$tender, $check]), [
        'is_checked' => 1,
    ])->assertRedirect();

    $check->refresh();

    expect($check->is_checked)->toBeTrue()
        ->and($check->checked_by_id)->toBe($employee->id)
        ->and($check->checked_at)->not->toBeNull();
});

it('clears the signature when a check is unticked', function () {
    [$director, $employee] = portalUser();
    $tender = Tender::factory()->create();
    $check = TenderSubmissionCheck::create([
        'tender_id' => $tender->id,
        'label' => 'EMD paid',
        'is_checked' => true,
        'checked_by_id' => $employee->id,
        'checked_at' => now(),
    ]);

    $this->actingAs($director)->put(route('admin.portal.tenders.submission.update', [$tender, $check]), [
        'is_checked' => 0,
    ]);

    $check->refresh();

    expect($check->is_checked)->toBeFalse()
        ->and($check->checked_by_id)->toBeNull()
        ->and($check->checked_at)->toBeNull();
});

it('refuses to mark a tender submitted while a check is outstanding', function () {
    [$director] = portalUser();
    $tender = Tender::factory()->create();
    TenderSubmissionCheck::create(['tender_id' => $tender->id, 'label' => 'Bid uploaded', 'is_checked' => false]);

    $this->actingAs($director)->post(route('admin.portal.tenders.submission.mark', $tender))
        ->assertSessionHasErrors('submission');

    expect($tender->refresh()->stage)->not->toBe(TenderStage::Submitted);
});

it('refuses to mark a tender submitted while a mandatory document is missing', function () {
    [$director] = portalUser();
    $tender = Tender::factory()->create();
    TenderSubmissionCheck::create(['tender_id' => $tender->id, 'label' => 'Bid uploaded', 'is_checked' => true]);
    TenderDocument::factory()->create(['tender_id' => $tender->id]);

    $this->actingAs($director)->post(route('admin.portal.tenders.submission.mark', $tender))
        ->assertSessionHasErrors('submission');

    expect($tender->refresh()->stage)->not->toBe(TenderStage::Submitted);
});

it('moves the tender to submitted once the checklist is genuinely clear', function () {
    [$director] = portalUser();
    $tender = Tender::factory()->create();
    TenderSubmissionCheck::create(['tender_id' => $tender->id, 'label' => 'Bid uploaded', 'is_checked' => true]);
    TenderDocument::factory()->ready()->create(['tender_id' => $tender->id]);

    $this->actingAs($director)->post(route('admin.portal.tenders.submission.mark', $tender))
        ->assertSessionHasNoErrors();

    expect($tender->refresh()->stage)->toBe(TenderStage::Submitted);
});

it('does not offer the submit button while the tender is not clear', function () {
    [$director] = portalUser();
    $tender = Tender::factory()->create();
    TenderSubmissionCheck::create(['tender_id' => $tender->id, 'label' => 'Bid uploaded', 'is_checked' => false]);

    $this->actingAs($director)->get(route('admin.portal.tenders.submission.index', $tender))
        ->assertOk()
        ->assertDontSee('Mark as Submitted')
        ->assertSee('Not clear to submit yet');
});

it('stops counting a deadline once the bid has gone in', function () {
    $preparing = Tender::factory()->deadlinePassed()->create();
    $submitted = Tender::factory()->deadlinePassed()->submitted()->create();

    expect($preparing->isDeadlinePassed())->toBeTrue()
        ->and($submitted->isDeadlinePassed())->toBeFalse();
});

it('keeps a settled document out of the missing count', function () {
    $tender = Tender::factory()->create();
    TenderDocument::factory()->create(['tender_id' => $tender->id, 'status' => TenderDocumentStatus::Rejected]);

    expect($tender->documents()->missingMandatory()->count())->toBe(1);
});
