<?php

use App\Enums\Portal\PortalRole;
use App\Models\Portal\DailyWorkUpdate;
use App\Models\Portal\Department;
use App\Models\Portal\Employee;
use App\Models\Portal\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * A smoke pass over every Phase 1 screen. Blade errors only surface at render
 * time, so each view is loaded once against real data.
 */
it('renders every portal screen for a director', function () {
    [$director, $employee] = portalUser(PortalRole::Director);

    $task = Task::factory()->assignedTo($employee)->create();
    $department = Department::factory()->create();
    $dailyWork = DailyWorkUpdate::factory()->forEmployee($employee)->create();

    $screens = [
        route('admin.portal.dashboard'),
        route('admin.portal.my-work'),
        route('admin.portal.tasks.index'),
        route('admin.portal.tasks.create'),
        route('admin.portal.tasks.show', $task),
        route('admin.portal.tasks.edit', $task),
        route('admin.portal.daily-work.index'),
        route('admin.portal.daily-work.create'),
        route('admin.portal.daily-work.edit', $dailyWork),
        route('admin.portal.employees.index'),
        route('admin.portal.employees.create'),
        route('admin.portal.employees.show', $employee),
        route('admin.portal.employees.edit', $employee),
        route('admin.portal.departments.index'),
        route('admin.portal.departments.create'),
        route('admin.portal.departments.edit', $department),
    ];

    foreach ($screens as $screen) {
        $this->actingAs($director)->get($screen)->assertOk();
    }
});

it('renders the screens an ordinary employee is allowed to open', function () {
    [$user, $employee] = portalUser(PortalRole::Employee);

    $task = Task::factory()->assignedTo($employee)->create();
    $dailyWork = DailyWorkUpdate::factory()->forEmployee($employee)->create();

    $screens = [
        route('admin.portal.dashboard'),
        route('admin.portal.my-work'),
        route('admin.portal.tasks.index'),
        route('admin.portal.tasks.create'),
        route('admin.portal.tasks.show', $task),
        route('admin.portal.tasks.edit', $task),
        route('admin.portal.daily-work.index'),
        route('admin.portal.daily-work.create'),
        route('admin.portal.daily-work.edit', $dailyWork),
        route('admin.portal.employees.index'),
        route('admin.portal.departments.index'),
    ];

    foreach ($screens as $screen) {
        $this->actingAs($user)->get($screen)->assertOk();
    }
});

it('hides CMS navigation from a portal-only user', function () {
    [$user] = portalUser(PortalRole::Employee);

    $this->actingAs($user)->get(route('admin.portal.dashboard'))
        ->assertOk()
        ->assertSee('Operations')
        ->assertDontSee('Website Management')
        ->assertDontSee(route('admin.leads.index'));
});

it('shows a CMS admin with portal access both sets of navigation', function () {
    $user = User::factory()->adminWithPortal()->create();
    Employee::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)->get(route('admin.portal.dashboard'))
        ->assertOk()
        ->assertSee('Operations')
        ->assertSee('Homepage');
});

it('gives a portal-only user a working logout and account page', function () {
    [$user] = portalUser(PortalRole::Employee);

    $this->actingAs($user)->get(route('admin.account.edit'))->assertOk();
    $this->actingAs($user)->post(route('admin.logout'))->assertRedirect();

    $this->assertGuest();
});
