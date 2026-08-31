<?php

use App\Enums\Portal\TaskStatus;
use App\Enums\Portal\TenderDocumentStatus;
use App\Models\Portal\Task;
use App\Models\Portal\Tender;
use App\Models\Portal\TenderChecklistItem;
use App\Models\Portal\TenderDocument;
use App\Services\Portal\TenderCompletionService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('reports zero for a tender with nothing on it', function () {
    $tender = Tender::factory()->create();

    expect(app(TenderCompletionService::class)->percent($tender))->toBe(0);
});

it('reaches a hundred when every area is finished', function () {
    $tender = Tender::factory()->create();
    TenderChecklistItem::factory()->met()->count(3)->create(['tender_id' => $tender->id]);
    TenderDocument::factory()->ready()->count(4)->create(['tender_id' => $tender->id]);

    expect(app(TenderCompletionService::class)->percent($tender->refresh()))->toBe(100);
});

it('excludes empty areas from the weighting', function () {
    // Only documents exist, and they are all settled: an empty OEM list must not
    // cap the tender below 100.
    $tender = Tender::factory()->create();
    TenderDocument::factory()->ready()->count(2)->create(['tender_id' => $tender->id]);

    expect(app(TenderCompletionService::class)->percent($tender->refresh()))->toBe(100);
});

it('weights documents more heavily than the submission checklist', function () {
    $documentsDone = Tender::factory()->create();
    TenderDocument::factory()->ready()->count(2)->create(['tender_id' => $documentsDone->id]);
    TenderChecklistItem::factory()->count(2)->create(['tender_id' => $documentsDone->id]);

    $eligibilityDone = Tender::factory()->create();
    TenderDocument::factory()->count(2)->create(['tender_id' => $eligibilityDone->id]);
    TenderChecklistItem::factory()->met()->count(2)->create(['tender_id' => $eligibilityDone->id]);

    $completion = app(TenderCompletionService::class);

    expect($completion->percent($documentsDone->refresh()))
        ->toBeGreaterThan($completion->percent($eligibilityDone->refresh()));
});

it('caches the percentage on the tender whenever a child record moves', function () {
    $tender = Tender::factory()->create();
    TenderDocument::factory()->count(2)->create(['tender_id' => $tender->id]);

    expect($tender->refresh()->completion_percent)->toBe(0);

    $tender->documents()->first()->update(['status' => TenderDocumentStatus::Ready]);

    expect($tender->refresh()->completion_percent)->toBe(50);
});

it('recomputes when a child record is deleted', function () {
    $tender = Tender::factory()->create();
    TenderDocument::factory()->ready()->create(['tender_id' => $tender->id]);
    $pending = TenderDocument::factory()->create(['tender_id' => $tender->id]);

    expect($tender->refresh()->completion_percent)->toBe(50);

    $pending->delete();

    expect($tender->refresh()->completion_percent)->toBe(100);
});

it('counts tender tasks towards readiness', function () {
    $tender = Tender::factory()->create();
    Task::factory()->count(2)->create([
        'related_type' => $tender->getMorphClass(),
        'related_id' => $tender->id,
        'status' => TaskStatus::NotStarted,
    ]);

    expect($tender->refresh()->completion_percent)->toBe(0);

    $tender->tasks()->first()->update(['status' => TaskStatus::Completed]);

    expect($tender->refresh()->completion_percent)->toBe(50);
});

it('summarises each area for the overview block', function () {
    $tender = Tender::factory()->create();
    TenderChecklistItem::factory()->met()->create(['tender_id' => $tender->id]);
    TenderChecklistItem::factory()->notMet()->create(['tender_id' => $tender->id]);
    TenderDocument::factory()->expired()->create(['tender_id' => $tender->id]);
    TenderDocument::factory()->create(['tender_id' => $tender->id]);

    $summary = app(TenderCompletionService::class)->summary($tender->refresh());

    expect($summary['eligibility']['met'])->toBe(1)
        ->and($summary['eligibility']['not_met'])->toBe(1)
        ->and($summary['eligibility']['answered'])->toBe(2)
        ->and($summary['documents']['expired'])->toBe(1)
        ->and($summary['documents']['mandatory_missing'])->toBe(1);
});

it('blocks submission readiness while a mandatory document is outstanding', function () {
    $tender = Tender::factory()->create();
    TenderDocument::factory()->create(['tender_id' => $tender->id]);

    $completion = app(TenderCompletionService::class);

    expect($completion->isReadyToSubmit($tender))->toBeFalse();

    $tender->documents()->first()->update(['status' => TenderDocumentStatus::Ready]);

    expect($completion->isReadyToSubmit($tender->refresh()))->toBeTrue();
});
