<?php

use App\Enums\UserRole;
use App\Models\Designation;
use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function designationManager(): User
{
    return User::factory()->role(UserRole::Hr)->create();
}

it('adds a designation that then appears in the careers form', function () {
    $hr = designationManager();

    $this->actingAs($hr)
        ->post(route('admin.designations.store'), ['name' => '  Cloud   Architect ', 'department' => 'Cloud', 'sort_order' => 5, 'is_active' => '1'])
        ->assertRedirect(route('admin.designations.index'));

    $designation = Designation::sole();

    expect($designation->name)->toBe('Cloud Architect')
        ->and($designation->created_by)->toBe($hr->id)
        ->and($designation->updated_by)->toBe($hr->id);

    $this->get(route('careers'))->assertSee('Cloud Architect');
});

it('rejects duplicate designation names', function () {
    Designation::factory()->create(['name' => 'QA Engineer']);

    $this->actingAs(designationManager())
        ->post(route('admin.designations.store'), ['name' => 'QA Engineer', 'is_active' => '1'])
        ->assertSessionHasErrors('name');

    expect(Designation::count())->toBe(1);
});

it('edits a designation and records who changed it', function () {
    $designation = Designation::factory()->create(['name' => 'Tester']);
    $editor = User::factory()->role(UserRole::Editor)->create();

    $this->actingAs($editor)
        ->put(route('admin.designations.update', $designation), ['name' => 'QA Analyst', 'sort_order' => 3, 'is_active' => '1'])
        ->assertRedirect(route('admin.designations.index'));

    expect($designation->fresh())
        ->name->toBe('QA Analyst')
        ->sort_order->toBe(3)
        ->updated_by->toBe($editor->id);
});

it('deactivating a designation removes it from the careers form', function () {
    $designation = Designation::factory()->create(['name' => 'Support Engineer']);

    $this->actingAs(designationManager())
        ->patch(route('admin.designations.toggle', $designation))
        ->assertRedirect();

    expect($designation->fresh()->is_active)->toBeFalse();
    $this->get(route('careers'))->assertDontSee('Support Engineer');
});

it('orders the public dropdown by display order', function () {
    Designation::factory()->create(['name' => 'Third', 'sort_order' => 30]);
    Designation::factory()->create(['name' => 'First', 'sort_order' => 10]);
    Designation::factory()->create(['name' => 'Second', 'sort_order' => 20]);

    $this->get(route('careers'))->assertSeeInOrder(['First', 'Second', 'Third']);
});

it('does not delete a designation used by applications', function () {
    $designation = Designation::factory()->create();
    JobApplication::factory()->forDesignation($designation)->create();

    $this->actingAs(designationManager())
        ->delete(route('admin.designations.destroy', $designation))
        ->assertSessionHasErrors('designation');

    $this->assertModelExists($designation);
});

it('deletes an unused designation', function () {
    $designation = Designation::factory()->create();

    $this->actingAs(designationManager())
        ->delete(route('admin.designations.destroy', $designation))
        ->assertRedirect(route('admin.designations.index'));

    $this->assertModelMissing($designation);
});

it('searches, filters and sorts the designation list', function () {
    Designation::factory()->create(['name' => 'Data Engineer', 'sort_order' => 2]);
    Designation::factory()->create(['name' => 'Data Analyst', 'sort_order' => 1, 'is_active' => false]);
    Designation::factory()->create(['name' => 'Designer', 'sort_order' => 3]);

    $hr = designationManager();

    $this->actingAs($hr)->get(route('admin.designations.index', ['q' => 'Data']))
        ->assertOk()->assertSee('Data Engineer')->assertSee('Data Analyst')->assertDontSee('Designer');

    $this->actingAs($hr)->get(route('admin.designations.index', ['status' => 'inactive']))
        ->assertOk()->assertSee('Data Analyst')->assertDontSee('Data Engineer');

    $this->actingAs($hr)->get(route('admin.designations.index', ['sort' => 'name', 'direction' => 'desc']))
        ->assertOk()->assertSeeInOrder(['Designer', 'Data Engineer', 'Data Analyst']);
});

it('denies designation changes to users without careers access', function (UserRole $role) {
    $designation = Designation::factory()->create();
    $user = User::factory()->role($role)->create();

    $this->actingAs($user)->post(route('admin.designations.store'), ['name' => 'Hacker', 'is_active' => '1'])->assertForbidden();
    $this->actingAs($user)->put(route('admin.designations.update', $designation), ['name' => 'Renamed'])->assertForbidden();
    $this->actingAs($user)->patch(route('admin.designations.toggle', $designation))->assertForbidden();

    expect(Designation::count())->toBe(1)
        ->and($designation->fresh()->is_active)->toBeTrue();
})->with([UserRole::Sales, UserRole::ReadOnly]);
