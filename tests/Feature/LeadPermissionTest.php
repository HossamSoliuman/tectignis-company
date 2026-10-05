<?php

use App\Enums\Portal\PortalRole;
use App\Enums\UserRole;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('lets every lead role view leads', function (UserRole $role) {
    $lead = Lead::factory()->create();
    $user = User::factory()->role($role)->create();

    $this->actingAs($user)->get(route('admin.leads.index'))->assertOk()->assertSee($lead->name);
    $this->actingAs($user)->get(route('admin.leads.show', $lead))->assertOk();
})->with([UserRole::SuperAdmin, UserRole::Editor, UserRole::Sales, UserRole::ReadOnly]);

it('keeps portal-only users and guests out of leads', function () {
    $lead = Lead::factory()->create();

    $this->get(route('admin.leads.index'))->assertRedirect(route('login'));

    $portalUser = User::factory()->portal(PortalRole::Director)->create();
    $this->actingAs($portalUser)->get(route('admin.leads.index'))->assertForbidden();
    $this->actingAs($portalUser)->get(route('admin.leads.show', $lead))->assertForbidden();
});

it('read-only users cannot change, annotate, delete or export leads', function () {
    $user = User::factory()->role(UserRole::ReadOnly)->create();
    $lead = Lead::factory()->create();

    $this->actingAs($user)->patch(route('admin.leads.update', $lead), ['status' => 'won'])->assertForbidden();
    $this->actingAs($user)->post(route('admin.leads.notes.store', $lead), ['body' => 'Sneaky'])->assertForbidden();
    $this->actingAs($user)->delete(route('admin.leads.destroy', $lead))->assertForbidden();
    $this->actingAs($user)->get(route('admin.leads.export'))->assertForbidden();

    $this->actingAs($user)->get(route('admin.leads.show', $lead))
        ->assertDontSee('Save changes')
        ->assertDontSee('Add note');

    expect($lead->fresh()->status->value)->toBe('new');
    $this->assertNotSoftDeleted($lead);
});

it('only super admins can delete leads', function () {
    $lead = Lead::factory()->create();

    $this->actingAs(User::factory()->role(UserRole::Sales)->create())
        ->delete(route('admin.leads.destroy', $lead))
        ->assertForbidden();

    $this->assertNotSoftDeleted($lead);
});

it('only super admins can see trashed leads', function () {
    $lead = Lead::factory()->create(['name' => 'Binned Lead']);
    $lead->delete();
    $sales = User::factory()->role(UserRole::Sales)->create();

    $this->actingAs($sales)->get(route('admin.leads.index', ['trashed' => 1]))->assertOk()->assertDontSee('Binned Lead');
    $this->actingAs($sales)->get(route('admin.leads.show', $lead))->assertNotFound();
});

it('sales and read-only users get leads without any CMS access', function (UserRole $role) {
    $user = User::factory()->role($role)->create();

    $this->actingAs($user)->get(route('admin.dashboard'))->assertForbidden();
    $this->actingAs($user)->get(route('admin.services.index'))->assertForbidden();
    $this->actingAs($user)->get(route('admin.leads.index'))
        ->assertOk()
        ->assertDontSee(route('admin.services.index'));
})->with([UserRole::Sales, UserRole::ReadOnly]);

it('sends sales users to leads after signing in', function () {
    $user = User::factory()->role(UserRole::Sales)->create();

    $this->post(route('admin.login.store'), ['email' => $user->email, 'password' => 'password'])
        ->assertRedirect(route('admin.leads.index'));
});
