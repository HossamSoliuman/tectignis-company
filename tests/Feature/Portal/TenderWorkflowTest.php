<?php

use App\Enums\Portal\TenderDecision;
use App\Enums\Portal\TenderDocumentStatus;
use App\Enums\Portal\TenderOutcome;
use App\Enums\Portal\TenderStage;
use App\Models\Portal\Tender;
use App\Models\Portal\TenderDocument;
use App\Models\Portal\TenderResult;
use App\Models\Portal\TenderSubmissionCheck;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('walks a tender from identified through to a recorded result', function () {
    [$director] = portalUser();

    $this->actingAs($director)->post(route('admin.portal.tenders.store'), [
        'tender_number' => 'GEM/2026/B/9001122',
        'title' => 'End to end bid',
        'portal' => 'gem',
        'stage' => TenderStage::Identified->value,
        'decision' => TenderDecision::UnderReview->value,
        'submission_deadline_at' => now()->addDays(20)->format('Y-m-d\TH:i'),
    ]);

    $tender = Tender::first();

    // Decide to bid.
    $this->actingAs($director)->put(route('admin.portal.tenders.update', $tender), [
        'tender_number' => $tender->tender_number,
        'title' => $tender->title,
        'portal' => 'gem',
        'stage' => TenderStage::DocumentationInProgress->value,
        'decision' => TenderDecision::Participate->value,
    ])->assertRedirect();

    // Clear the paperwork and the final checklist.
    $tender->documents()->update(['status' => TenderDocumentStatus::Ready]);
    $tender->submissionChecks()->update(['is_checked' => true]);

    $this->actingAs($director)->post(route('admin.portal.tenders.submission.mark', $tender))
        ->assertSessionHasNoErrors();

    expect($tender->refresh()->stage)->toBe(TenderStage::Submitted);

    // Record the outcome.
    $this->actingAs($director)->put(route('admin.portal.tenders.result.store', $tender), [
        'outcome' => TenderOutcome::Won->value,
        'awarded_value' => 4500000,
        'financial_result' => 'L1',
    ])->assertRedirect();

    $tender->refresh();

    expect($tender->stage)->toBe(TenderStage::Closed)
        ->and($tender->result->outcome)->toBe(TenderOutcome::Won)
        ->and((float) $tender->result->awarded_value)->toBe(4500000.0);
});

it('leaves a tender awaiting its result when the outcome is still pending', function () {
    [$director] = portalUser();
    $tender = Tender::factory()->submitted()->create();

    $this->actingAs($director)->put(route('admin.portal.tenders.result.store', $tender), [
        'outcome' => TenderOutcome::Pending->value,
    ])->assertRedirect();

    expect($tender->refresh()->stage)->toBe(TenderStage::ResultAwaited);
});

it('demands an awarded value for a won tender', function () {
    [$director] = portalUser();
    $tender = Tender::factory()->submitted()->create();

    $this->actingAs($director)->put(route('admin.portal.tenders.result.store', $tender), [
        'outcome' => TenderOutcome::Won->value,
    ])->assertSessionHasErrors('awarded_value');

    expect($tender->refresh()->result)->toBeNull();
});

it('corrects a result rather than creating a second one', function () {
    [$director] = portalUser();
    $tender = Tender::factory()->submitted()->create();

    $this->actingAs($director)->put(route('admin.portal.tenders.result.store', $tender), [
        'outcome' => TenderOutcome::Lost->value,
        'remarks' => 'Beaten on price.',
    ]);

    $this->actingAs($director)->put(route('admin.portal.tenders.result.store', $tender), [
        'outcome' => TenderOutcome::Cancelled->value,
        'remarks' => 'Tender was withdrawn by the authority.',
    ]);

    expect($tender->refresh()->result->outcome)->toBe(TenderOutcome::Cancelled)
        ->and(TenderResult::count())->toBe(1);
});

it('keeps the award letter with the result', function () {
    Storage::fake(config('portal.disk', 'portal'));

    [$director] = portalUser();
    $tender = Tender::factory()->submitted()->create();

    $this->actingAs($director)->put(route('admin.portal.tenders.result.store', $tender), [
        'outcome' => TenderOutcome::Won->value,
        'awarded_value' => 100000,
        'attachment' => UploadedFile::fake()->create('award-letter.pdf', 40, 'application/pdf'),
    ]);

    expect($tender->refresh()->result->attachments)->toHaveCount(1);
});

it('drops a do-not-participate tender out of the active list', function () {
    Tender::factory()->create(['title' => 'We are bidding', 'decision' => TenderDecision::Participate]);
    Tender::factory()->create(['title' => 'We are not bidding', 'decision' => TenderDecision::DoNotParticipate]);
    Tender::factory()->closed()->create(['title' => 'Already closed']);

    expect(Tender::query()->active()->pluck('title')->all())->toBe(['We are bidding']);
});

it('gathers the whole workspace history on the activity tab', function () {
    [$director] = portalUser();
    $tender = Tender::factory()->create();
    $document = TenderDocument::factory()->create(['tender_id' => $tender->id, 'name' => 'Audited Balance Sheet']);
    TenderSubmissionCheck::create(['tender_id' => $tender->id, 'label' => 'Bid uploaded']);

    $this->actingAs($director)->put(route('admin.portal.tenders.documents.update', [$tender, $document]), [
        'status' => TenderDocumentStatus::Ready->value,
    ]);

    $this->actingAs($director)->get(route('admin.portal.tenders.activity.index', $tender))
        ->assertOk()
        ->assertSee('Audited Balance Sheet');
});
