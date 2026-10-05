<?php

use App\Enums\LeadStatus;
use App\Enums\UserRole;
use App\Models\Lead;
use App\Models\LeadActivity;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function leadUser(UserRole $role = UserRole::Sales): User
{
    return User::factory()->role($role)->create();
}

it('shows the lead detail with attribution and marks it read', function () {
    $lead = Lead::factory()->enquiry()->create(['utm_source' => 'google', 'company' => 'Initech']);

    $this->actingAs(leadUser())
        ->get(route('admin.leads.show', $lead))
        ->assertOk()
        ->assertSee('Initech')
        ->assertSee('google')
        ->assertSee($lead->reference());

    expect($lead->fresh()->is_read)->toBeTrue();
});

it('sales can change the status and the change is audited', function () {
    $user = leadUser();
    $lead = Lead::factory()->create();

    $this->actingAs($user)
        ->patch(route('admin.leads.update', $lead), ['status' => 'qualified'])
        ->assertRedirect()
        ->assertSessionHas('status');

    expect($lead->fresh()->status)->toBe(LeadStatus::Qualified);

    $this->assertDatabaseHas('lead_activities', [
        'lead_id' => $lead->id,
        'user_id' => $user->id,
        'type' => LeadActivity::STATUS_CHANGED,
        'old_value' => 'new',
        'new_value' => 'qualified',
    ]);
});

it('sales can assign and reassign a lead', function () {
    $user = leadUser();
    $first = leadUser();
    $second = leadUser(UserRole::Editor);
    $lead = Lead::factory()->create();

    $this->actingAs($user)->patch(route('admin.leads.update', $lead), ['status' => 'new', 'assigned_to' => $first->id]);
    $this->actingAs($user)->patch(route('admin.leads.update', $lead), ['status' => 'new', 'assigned_to' => $second->id]);

    expect($lead->fresh()->assigned_to)->toBe($second->id)
        ->and($lead->activities()->where('type', LeadActivity::ASSIGNED)->count())->toBe(2)
        ->and($lead->activities()->where('type', LeadActivity::STATUS_CHANGED)->count())->toBe(0);
});

it('leads cannot be assigned to read-only or portal users', function () {
    $lead = Lead::factory()->create();

    $this->actingAs(leadUser())
        ->patch(route('admin.leads.update', $lead), ['status' => 'new', 'assigned_to' => leadUser(UserRole::ReadOnly)->id])
        ->assertSessionHasErrors('assigned_to');

    expect($lead->fresh()->assigned_to)->toBeNull();
});

it('sales can add private notes stamped with author', function () {
    $user = leadUser();
    $lead = Lead::factory()->create();

    $this->actingAs($user)
        ->post(route('admin.leads.notes.store', $lead), ['body' => 'Called — wants a proposal by Friday.'])
        ->assertRedirect();

    $this->assertDatabaseHas('lead_notes', ['lead_id' => $lead->id, 'user_id' => $user->id, 'body' => 'Called — wants a proposal by Friday.']);

    $this->actingAs($user)->get(route('admin.leads.show', $lead))
        ->assertSee('Called — wants a proposal by Friday.')
        ->assertSee($user->name);
});

it('filters leads by every supported filter', function (array $query, string $expected) {
    $owner = leadUser();

    Lead::factory()->create(['name' => 'Alpha Lead', 'status' => LeadStatus::Won, 'service' => 'AWS', 'country' => 'India', 'source' => 'contact', 'created_at' => now()->subDays(40)]);
    Lead::factory()->assignedTo($owner)->create(['name' => 'Bravo Lead', 'status' => LeadStatus::Contacted, 'service' => 'SaaS', 'country' => 'Canada', 'source' => 'consultation', 'created_at' => now()]);

    $query = array_map(fn ($value) => $value === ':owner' ? $owner->id : $value, $query);

    $response = $this->actingAs(leadUser())->get(route('admin.leads.index', $query))->assertOk();

    $response->assertSee($expected);
    $response->assertDontSee($expected === 'Alpha Lead' ? 'Bravo Lead' : 'Alpha Lead');
})->with([
    'status' => [['status' => 'won'], 'Alpha Lead'],
    'service' => [['service' => 'SaaS'], 'Bravo Lead'],
    'country' => [['country' => 'India'], 'Alpha Lead'],
    'assigned user' => [['assigned_to' => ':owner'], 'Bravo Lead'],
    'unassigned' => [['assigned_to' => 'unassigned'], 'Alpha Lead'],
    'source' => [['source' => 'consultation'], 'Bravo Lead'],
    'date range' => [['date_from' => now()->subDays(50)->toDateString(), 'date_to' => now()->subDays(30)->toDateString()], 'Alpha Lead'],
]);

it('searches by name, company, email, phone and lead id', function (string $term) {
    $match = Lead::factory()->create(['name' => 'Wanted Person', 'company' => 'Umbrella Corp', 'email' => 'wanted@umbrella.example', 'phone' => '+44 20 7946 0000']);
    Lead::factory()->create(['name' => 'Other Person', 'company' => 'Cyberdyne', 'email' => 'other@cyberdyne.example', 'phone' => '+1 555 0100']);

    $term = $term === ':id' ? $match->reference() : $term;

    $this->actingAs(leadUser())
        ->get(route('admin.leads.index', ['q' => $term]))
        ->assertSee('Wanted Person')
        ->assertDontSee('Other Person');
})->with(['Wanted', 'Umbrella', 'wanted@umbrella', '7946', ':id']);

it('paginates server-side and keeps filters in the page links', function () {
    Lead::factory()->count(12)->create(['service' => 'AWS']);

    $response = $this->actingAs(leadUser())
        ->get(route('admin.leads.index', ['service' => 'AWS', 'per_page' => 10]))
        ->assertOk()
        ->assertSee('Showing')
        ->assertSee('1–10')
        ->assertSee('of')
        ->assertSee('12');

    expect($response->viewData('leads')->count())->toBe(10);
    $response->assertSee('service=AWS', false)->assertSee('per_page=10', false)->assertSee('page=2', false);
});

it('falls back to 20 rows for an unsupported page size', function () {
    Lead::factory()->count(25)->create();

    $this->actingAs(leadUser())
        ->get(route('admin.leads.index', ['per_page' => 1000]))
        ->assertSessionHasErrors('per_page');

    $response = $this->actingAs(leadUser())->get(route('admin.leads.index'))->assertOk();
    expect($response->viewData('leads')->perPage())->toBe(20);
});

it('pipeline counts follow the active filters', function () {
    Lead::factory()->count(2)->create(['country' => 'India', 'status' => LeadStatus::Won]);
    Lead::factory()->count(3)->create(['country' => 'Canada', 'status' => LeadStatus::Won]);

    $response = $this->actingAs(leadUser())->get(route('admin.leads.index', ['country' => 'India']))->assertOk();

    expect($response->viewData('statusCounts')['won'])->toBe(2);
});

it('super admin soft-deletes and restores leads with an audit trail', function () {
    $admin = leadUser(UserRole::SuperAdmin);
    $lead = Lead::factory()->create();

    $this->actingAs($admin)->delete(route('admin.leads.destroy', $lead))->assertRedirect(route('admin.leads.index'));
    $this->assertSoftDeleted($lead);

    $this->actingAs($admin)->get(route('admin.leads.index', ['trashed' => 1]))->assertSee($lead->name);

    $this->actingAs($admin)->patch(route('admin.leads.restore', $lead))->assertRedirect(route('admin.leads.show', $lead));
    $this->assertNotSoftDeleted($lead);

    expect($lead->activities()->pluck('type')->all())->toContain(LeadActivity::DELETED, LeadActivity::RESTORED);
});

it('sorts by an allowed column only', function () {
    Lead::factory()->create(['company' => 'Zeta']);
    Lead::factory()->create(['company' => 'Alpha']);

    $response = $this->actingAs(leadUser())->get(route('admin.leads.index', ['sort' => 'company', 'direction' => 'asc']))->assertOk();
    expect($response->viewData('leads')->first()->company)->toBe('Alpha');

    $this->actingAs(leadUser())->get(route('admin.leads.index', ['sort' => 'password']))->assertSessionHasErrors('sort');
});
