<?php

use App\Enums\Portal\ClarificationStatus;
use App\Enums\Portal\PortalRole;
use App\Models\Portal\Employee;
use App\Models\Portal\Tender;
use App\Models\Portal\TenderClarification;
use App\Models\User;
use App\Services\Portal\TenderCompletionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('records a query against the person who raised it', function () {
    [$director, $employee] = portalUser();
    $tender = Tender::factory()->create();

    $this->actingAs($director)->post(route('admin.portal.tenders.clarifications.store', $tender), [
        'question' => 'Does the turnover requirement include group companies?',
        'submitted_through' => 'GeM query',
    ])->assertRedirect();

    $clarification = TenderClarification::first();

    expect($clarification->raised_by_id)->toBe($employee->id)
        ->and($clarification->status)->toBe(ClarificationStatus::Pending)
        ->and($clarification->raised_on)->not->toBeNull();
});

it('stamps the answer date when a response is first recorded', function () {
    [$director] = portalUser();
    $tender = Tender::factory()->create();
    $clarification = TenderClarification::create([
        'tender_id' => $tender->id,
        'question' => 'Is an OEM datasheet mandatory?',
    ]);

    $this->actingAs($director)->put(route('admin.portal.tenders.clarifications.update', [$tender, $clarification]), [
        'response' => 'Yes, for every quoted line item.',
        'status' => ClarificationStatus::Answered->value,
    ])->assertRedirect();

    $clarification->refresh();

    expect($clarification->response_on)->not->toBeNull()
        ->and($clarification->status)->toBe(ClarificationStatus::Answered);
});

it('keeps the reply document alongside the answer', function () {
    Storage::fake(config('portal.disk', 'portal'));

    [$director] = portalUser();
    $tender = Tender::factory()->create();
    $clarification = TenderClarification::create([
        'tender_id' => $tender->id,
        'question' => 'Please confirm the delivery schedule.',
    ]);

    $this->actingAs($director)->put(route('admin.portal.tenders.clarifications.update', [$tender, $clarification]), [
        'response' => 'See attached corrigendum.',
        'status' => ClarificationStatus::Answered->value,
        'attachment' => UploadedFile::fake()->create('corrigendum.pdf', 40, 'application/pdf'),
    ]);

    $clarification->refresh();

    expect($clarification->attachment_id)->not->toBeNull()
        ->and($clarification->attachments)->toHaveCount(1);
});

it('requires a question', function () {
    [$director] = portalUser();
    $tender = Tender::factory()->create();

    $this->actingAs($director)->post(route('admin.portal.tenders.clarifications.store', $tender), [
        'question' => '',
    ])->assertSessionHasErrors('question');

    expect(TenderClarification::count())->toBe(0);
});

it('counts unanswered queries in the tender summary', function () {
    $tender = Tender::factory()->create();
    TenderClarification::create(['tender_id' => $tender->id, 'question' => 'One?']);
    TenderClarification::create([
        'tender_id' => $tender->id,
        'question' => 'Two?',
        'status' => ClarificationStatus::Answered,
    ]);

    $summary = app(TenderCompletionService::class)->summary($tender);

    expect($summary['clarifications']['total'])->toBe(2)
        ->and($summary['clarifications']['pending'])->toBe(1)
        ->and($summary['clarifications']['answered'])->toBe(1);
});

it('will not let a clarification be reached through another tender', function () {
    [$director] = portalUser();
    $tender = Tender::factory()->create();
    $otherTender = Tender::factory()->create();
    $clarification = TenderClarification::create(['tender_id' => $tender->id, 'question' => 'Scoped?']);

    $this->actingAs($director)
        ->put(route('admin.portal.tenders.clarifications.update', [$otherTender, $clarification]), [
            'status' => ClarificationStatus::Closed->value,
        ])
        ->assertNotFound();
});

it('hides a tender workspace from an employee outside the bid', function () {
    $user = User::factory()->portal(PortalRole::Employee)->create();
    Employee::factory()->create(['user_id' => $user->id]);
    $tender = Tender::factory()->create();

    $this->actingAs($user)->get(route('admin.portal.tenders.clarifications.index', $tender))->assertForbidden();
});
