<?php

use App\Enums\Portal\PortalRole;
use App\Models\Portal\Department;
use App\Models\Portal\Employee;
use App\Models\Portal\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('rejects guests from every portal route', function (string $route) {
    $this->get(route($route))->assertRedirect(route('login'));
})->with(['admin.portal.dashboard', 'admin.portal.my-work', 'admin.portal.tasks.index', 'admin.portal.daily-work.index']);

it('rejects a CMS admin who has no portal role', function () {
    $user = User::factory()->create(['role' => 'admin', 'portal_role' => null]);

    $this->actingAs($user)->get(route('admin.portal.dashboard'))->assertForbidden();
});

it('lets a portal-only employee reach the portal but not the CMS', function () {
    $user = User::factory()->portal(PortalRole::Employee)->create();

    $this->actingAs($user)->get(route('admin.portal.dashboard'))->assertOk();
    $this->actingAs($user)->get(route('admin.dashboard'))->assertForbidden();
});

it('admits every portal role to the read-only screens', function (PortalRole $role) {
    $user = User::factory()->portal($role)->create();
    Employee::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)->get(route('admin.portal.dashboard'))->assertOk();
    $this->actingAs($user)->get(route('admin.portal.tasks.index'))->assertOk();
    $this->actingAs($user)->get(route('admin.portal.employees.index'))->assertOk();
    $this->actingAs($user)->get(route('admin.portal.departments.index'))->assertOk();
})->with([PortalRole::Director, PortalRole::Manager, PortalRole::Employee, PortalRole::Viewer]);

it('blocks viewers from creating anything', function () {
    $user = User::factory()->portal(PortalRole::Viewer)->create();
    Employee::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)->get(route('admin.portal.tasks.create'))->assertForbidden();
    $this->actingAs($user)->post(route('admin.portal.tasks.store'), [
        'title' => 'Should not be created',
        'priority' => 'medium',
        'status' => 'not_started',
    ])->assertForbidden();

    expect(Task::count())->toBe(0);
});

it('keeps employee creation out of an employee\'s hands', function () {
    $user = User::factory()->portal(PortalRole::Employee)->create();
    Employee::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)->get(route('admin.portal.employees.create'))->assertForbidden();
});

it('lets managers create employees but only directors delete them', function () {
    $manager = User::factory()->portal(PortalRole::Manager)->create();
    Employee::factory()->create(['user_id' => $manager->id]);
    $target = Employee::factory()->create();

    $this->actingAs($manager)->get(route('admin.portal.employees.create'))->assertOk();
    $this->actingAs($manager)->delete(route('admin.portal.employees.destroy', $target))->assertForbidden();

    $director = User::factory()->portal(PortalRole::Director)->create();
    $this->actingAs($director)->delete(route('admin.portal.employees.destroy', $target))->assertRedirect();

    expect(Employee::find($target->id))->toBeNull();
});

it('restricts department deletion to directors', function () {
    $manager = User::factory()->portal(PortalRole::Manager)->create();
    Employee::factory()->create(['user_id' => $manager->id]);
    $department = Department::factory()->create();

    $this->actingAs($manager)->delete(route('admin.portal.departments.destroy', $department))->assertForbidden();

    $director = User::factory()->portal(PortalRole::Director)->create();
    $this->actingAs($director)->delete(route('admin.portal.departments.destroy', $department))->assertRedirect();
});

it('signs a portal-only user in and lands them on the portal dashboard', function () {
    User::factory()->portal(PortalRole::Employee)->create([
        'email' => 'employee@tectignis.in',
        'password' => bcrypt('secret'),
    ]);

    $this->post(route('admin.login.store'), [
        'email' => 'employee@tectignis.in',
        'password' => 'secret',
    ])->assertRedirect(route('admin.portal.dashboard'));
});

it('still turns away a user holding neither role', function () {
    User::factory()->create([
        'email' => 'nobody@tectignis.in',
        'password' => bcrypt('secret'),
        'role' => 'user',
        'portal_role' => null,
    ]);

    $this->post(route('admin.login.store'), [
        'email' => 'nobody@tectignis.in',
        'password' => 'secret',
    ])->assertSessionHasErrors('email');

    $this->assertGuest();
});
