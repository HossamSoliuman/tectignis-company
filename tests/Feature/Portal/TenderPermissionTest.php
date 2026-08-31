<?php

use App\Enums\Portal\PortalRole;
use App\Enums\Portal\TenderOutcome;
use App\Models\Portal\Employee;
use App\Models\Portal\Task;
use App\Models\Portal\Tender;
use App\Models\Portal\TenderDocument;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * A signed-in portal user at the given role together with their employee row.
 *
 * @return array{0: User, 1: Employee}
 */
function tenderUser(PortalRole $role): array
{
    $user = User::factory()->portal($role)->create();

    return [$user, Employee::factory()->create(['user_id' => $user->id])];
}

it('shows a manager every tender', function () {
    [$manager] = tenderUser(PortalRole::Manager);
    Tender::factory()->create(['title' => 'Somebody else bid']);

    $this->actingAs($manager)->get(route('admin.portal.tenders.index'))
        ->assertOk()
        ->assertSee('Somebody else bid');
});

it('shows an employee only the bids they are part of', function () {
    [$user, $employee] = tenderUser(PortalRole::Employee);

    Tender::factory()->create(['title' => 'My own bid', 'assigned_employee_id' => $employee->id]);
    Tender::factory()->create(['title' => 'Not my bid']);

    $this->actingAs($user)->get(route('admin.portal.tenders.index'))
        ->assertOk()
        ->assertSee('My own bid')
        ->assertDontSee('Not my bid');
});

it('lets an employee reach a tender through a task assigned to them', function () {
    [$user, $employee] = tenderUser(PortalRole::Employee);
    $tender = Tender::factory()->create(['title' => 'Bid I carry a task on']);
    Task::factory()->assignedTo($employee)->create([
        'related_type' => $tender->getMorphClass(),
        'related_id' => $tender->id,
    ]);

    $this->actingAs($user)->get(route('admin.portal.tenders.show', $tender))->assertOk();
});

it('keeps the workspace closed to an employee outside the bid', function () {
    [$user] = tenderUser(PortalRole::Employee);
    $tender = Tender::factory()->create();

    foreach ([
        route('admin.portal.tenders.show', $tender),
        route('admin.portal.tenders.eligibility.index', $tender),
        route('admin.portal.tenders.documents.index', $tender),
        route('admin.portal.tenders.submission.index', $tender),
        route('admin.portal.tenders.activity.index', $tender),
    ] as $url) {
        $this->actingAs($user)->get($url)->assertForbidden();
    }
});

it('gives a viewer read access to their own bids but no write access anywhere', function () {
    // Viewers are scoped like employees (they see their own records) and, on top
    // of that, cannot write at all.
    [$viewer, $employee] = tenderUser(PortalRole::Viewer);
    $tender = Tender::factory()->create(['title' => 'Read only bid', 'assigned_employee_id' => $employee->id]);

    $this->actingAs($viewer)->get(route('admin.portal.tenders.index'))->assertOk()->assertSee('Read only bid');
    $this->actingAs($viewer)->get(route('admin.portal.tenders.show', $tender))->assertOk();

    $this->actingAs($viewer)->get(route('admin.portal.tenders.create'))->assertForbidden();
    $this->actingAs($viewer)->post(route('admin.portal.tenders.clarifications.store', $tender), [
        'question' => 'May I write?',
    ])->assertForbidden();
});

it('reserves recording the result for management', function () {
    [$user, $employee] = tenderUser(PortalRole::Employee);
    $tender = Tender::factory()->submitted()->create(['assigned_employee_id' => $employee->id]);

    $this->actingAs($user)->put(route('admin.portal.tenders.result.store', $tender), [
        'outcome' => TenderOutcome::Won->value,
        'awarded_value' => 1000,
    ])->assertForbidden();

    [$manager] = tenderUser(PortalRole::Manager);

    $this->actingAs($manager)->put(route('admin.portal.tenders.result.store', $tender), [
        'outcome' => TenderOutcome::Won->value,
        'awarded_value' => 1000,
    ])->assertRedirect();
});

it('shuts a user with no portal role out entirely', function () {
    $outsider = User::factory()->create(['portal_role' => null]);
    $tender = Tender::factory()->create();

    $this->actingAs($outsider)->get(route('admin.portal.tenders.index'))->assertForbidden();
    $this->actingAs($outsider)->get(route('admin.portal.tenders.show', $tender))->assertForbidden();
});

it('scopes a child record to the tender in the url', function () {
    [$director] = portalUser();
    $tender = Tender::factory()->create();
    $otherTender = Tender::factory()->create();
    $document = TenderDocument::factory()->create(['tender_id' => $tender->id]);

    $this->actingAs($director)
        ->put(route('admin.portal.tenders.documents.update', [$otherTender, $document]), ['status' => 'ready'])
        ->assertNotFound();
});
