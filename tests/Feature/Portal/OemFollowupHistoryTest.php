<?php

use App\Enums\Portal\OemFollowupStatus;
use App\Enums\Portal\PortalRole;
use App\Models\Portal\Employee;
use App\Models\Portal\OemFollowup;
use App\Models\Portal\Tender;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('appends a contact instead of overwriting the last one', function () {
    [$director] = portalUser();
    $followup = OemFollowup::factory()->create(['status' => OemFollowupStatus::Requested]);

    foreach (['Called, no answer.', 'Emailed the channel manager.', 'Promised by Friday.'] as $note) {
        $this->actingAs($director)->post(route('admin.portal.oem-followups.updates.store', $followup), [
            'note' => $note,
        ])->assertRedirect(route('admin.portal.oem-followups.show', $followup));
    }

    expect($followup->refresh()->followupUpdates)->toHaveCount(3)
        ->and($followup->followupUpdates->pluck('note')->all())->toContain('Called, no answer.');
});

it('records the status transition on each contact', function () {
    [$director] = portalUser();
    $followup = OemFollowup::factory()->create(['status' => OemFollowupStatus::Requested]);

    $this->actingAs($director)->post(route('admin.portal.oem-followups.updates.store', $followup), [
        'note' => 'They have started processing it.',
        'status' => OemFollowupStatus::InProcess->value,
    ]);

    $update = $followup->refresh()->followupUpdates->first();

    expect($followup->status)->toBe(OemFollowupStatus::InProcess)
        ->and($update->status_from)->toBe(OemFollowupStatus::Requested->value)
        ->and($update->status_to)->toBe(OemFollowupStatus::InProcess->value);
});

it('leaves the existing chase date alone when a contact sets no new one', function () {
    [$director] = portalUser();
    $followup = OemFollowup::factory()->create(['next_followup_at' => now()->addDays(3)]);
    $original = $followup->next_followup_at;

    $this->actingAs($director)->post(route('admin.portal.oem-followups.updates.store', $followup), [
        'note' => 'Left a voicemail.',
    ]);

    expect($followup->refresh()->next_followup_at->toDateString())->toBe($original->toDateString());
});

it('attaches a file to a contact entry', function () {
    Storage::fake(config('portal.disk', 'portal'));

    [$director] = portalUser();
    $followup = OemFollowup::factory()->create();

    $this->actingAs($director)->post(route('admin.portal.oem-followups.updates.store', $followup), [
        'note' => 'MAF received.',
        'status' => OemFollowupStatus::Received->value,
        'attachment' => UploadedFile::fake()->create('maf.pdf', 30, 'application/pdf'),
    ]);

    expect($followup->refresh()->followupUpdates->first()->attachments)->toHaveCount(1);
});

it('surfaces chases that are due and hides ones already received', function () {
    [$director] = portalUser();

    OemFollowup::factory()->due()->create(['oem_name' => 'Chase Me Ltd']);
    OemFollowup::factory()->received()->create(['oem_name' => 'Already Delivered Ltd']);

    $this->actingAs($director)->get(route('admin.portal.oem-followups.index', ['due' => 1]))
        ->assertOk()
        ->assertSee('Chase Me Ltd')
        ->assertDontSee('Already Delivered Ltd');
});

it('treats a follow-up past its required-by date as overdue', function () {
    $late = OemFollowup::factory()->overdue()->create();
    $onTime = OemFollowup::factory()->create();
    $delivered = OemFollowup::factory()->received()->create(['required_by' => now()->subWeek()]);

    expect($late->isOverdue())->toBeTrue()
        ->and($onTime->isOverdue())->toBeFalse()
        ->and($delivered->isOverdue())->toBeFalse();
});

it('hides a chase on a bid the employee is not part of', function () {
    $user = User::factory()->portal(PortalRole::Employee)->create();
    $employee = Employee::factory()->create(['user_id' => $user->id]);

    $mine = Tender::factory()->create(['assigned_employee_id' => $employee->id]);
    OemFollowup::factory()->create(['tender_id' => $mine->id, 'oem_name' => 'My Bid OEM']);
    OemFollowup::factory()->create(['oem_name' => 'Somebody Else OEM']);
    OemFollowup::factory()->standalone()->create(['oem_name' => 'Unattached OEM']);

    $this->actingAs($user)->get(route('admin.portal.oem-followups.index'))
        ->assertOk()
        ->assertSee('My Bid OEM')
        ->assertSee('Unattached OEM')
        ->assertDontSee('Somebody Else OEM');
});

it('raises a chase against the tender in the url, not the form', function () {
    [$director] = portalUser();
    $tender = Tender::factory()->create();
    $otherTender = Tender::factory()->create();

    $this->actingAs($director)->post(route('admin.portal.tenders.oem.store', $tender), [
        'tender_id' => $otherTender->id,
        'oem_name' => 'Cisco Systems',
        'requirement_type' => 'maf',
        'status' => 'requested',
    ])->assertRedirect();

    expect(OemFollowup::first()->tender_id)->toBe($tender->id);
});
