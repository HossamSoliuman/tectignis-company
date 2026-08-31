<?php

use App\Enums\Portal\PortalRole;
use App\Models\Portal\Employee;
use App\Models\Portal\Tender;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates a tender with an auto-generated code and its full workspace', function () {
    [$director] = portalUser();
    $owner = Employee::factory()->create();

    $this->actingAs($director)->post(route('admin.portal.tenders.store'), [
        'tender_number' => 'GEM/2026/B/1234567',
        'title' => 'Supply of network switches',
        'customer_organization' => 'Department of IT',
        'portal' => 'gem',
        'submission_deadline_at' => now()->addDays(10)->format('Y-m-d\TH:i'),
        'assigned_employee_id' => $owner->id,
        'stage' => 'identified',
        'decision' => 'under_review',
    ])->assertRedirect();

    $tender = Tender::first();

    expect($tender->code)->toStartWith('TND-')
        ->and($tender->title)->toBe('Supply of network switches')
        ->and($tender->checklistItems)->not->toBeEmpty()
        ->and($tender->documents)->not->toBeEmpty()
        ->and($tender->submissionChecks)->not->toBeEmpty()
        ->and($tender->tasks)->not->toBeEmpty();
});

it('gives each tender a distinct code', function () {
    [$director] = portalUser();

    foreach (['First tender', 'Second tender'] as $title) {
        $this->actingAs($director)->post(route('admin.portal.tenders.store'), [
            'tender_number' => 'TN/'.$title,
            'title' => $title,
            'portal' => 'gem',
            'stage' => 'identified',
            'decision' => 'under_review',
        ]);
    }

    expect(Tender::pluck('code')->unique())->toHaveCount(2);
});

it('can skip the task template while still building the checklists', function () {
    [$director] = portalUser();

    $this->actingAs($director)->post(route('admin.portal.tenders.store'), [
        'tender_number' => 'GEM/2026/B/7654321',
        'title' => 'No tasks please',
        'portal' => 'gem',
        'stage' => 'identified',
        'decision' => 'under_review',
        'apply_task_template' => '0',
    ])->assertRedirect();

    $tender = Tender::first();

    expect($tender->tasks)->toBeEmpty()
        ->and($tender->documents)->not->toBeEmpty();
});

it('rejects a deadline that falls before submissions open', function () {
    [$director] = portalUser();

    $this->actingAs($director)->post(route('admin.portal.tenders.store'), [
        'tender_number' => 'GEM/2026/B/0000001',
        'title' => 'Impossible schedule',
        'portal' => 'gem',
        'stage' => 'identified',
        'decision' => 'under_review',
        'submission_start_at' => now()->addDays(10)->format('Y-m-d\TH:i'),
        'submission_deadline_at' => now()->addDays(2)->format('Y-m-d\TH:i'),
    ])->assertSessionHasErrors('submission_deadline_at');

    expect(Tender::count())->toBe(0);
});

it('demands an EMD amount when EMD is required', function () {
    [$director] = portalUser();

    $this->actingAs($director)->post(route('admin.portal.tenders.store'), [
        'tender_number' => 'GEM/2026/B/0000002',
        'title' => 'EMD without a number',
        'portal' => 'gem',
        'stage' => 'identified',
        'decision' => 'under_review',
        'emd_required' => '1',
    ])->assertSessionHasErrors('emd_amount');
});

it('filters the tender list by stage and closing-soon', function () {
    [$director] = portalUser();

    Tender::factory()->closingSoon()->create(['title' => 'Closes this week']);
    Tender::factory()->create(['title' => 'Closes next month', 'submission_deadline_at' => now()->addDays(45)]);

    $this->actingAs($director)->get(route('admin.portal.tenders.index', ['closing_soon' => 1]))
        ->assertOk()
        ->assertSee('Closes this week')
        ->assertDontSee('Closes next month');
});

it('soft deletes a tender so the bid history survives', function () {
    [$director] = portalUser();
    $tender = Tender::factory()->create();

    $this->actingAs($director)->delete(route('admin.portal.tenders.destroy', $tender))->assertRedirect();

    expect(Tender::find($tender->id))->toBeNull()
        ->and(Tender::withTrashed()->find($tender->id))->not->toBeNull();
});

it('will not let a manager delete a tender', function () {
    $user = User::factory()->portal(PortalRole::Manager)->create();
    Employee::factory()->create(['user_id' => $user->id]);
    $tender = Tender::factory()->create();

    $this->actingAs($user)->delete(route('admin.portal.tenders.destroy', $tender))->assertForbidden();

    expect(Tender::find($tender->id))->not->toBeNull();
});
