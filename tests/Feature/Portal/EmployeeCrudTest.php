<?php

use App\Enums\Portal\EmployeeStatus;
use App\Enums\Portal\PortalRole;
use App\Models\Portal\Department;
use App\Models\Portal\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates an employee and generates a code when none is given', function () {
    [$director] = portalUser(PortalRole::Director);
    $department = Department::factory()->create();

    $this->actingAs($director)->post(route('admin.portal.employees.store'), [
        'name' => 'Rahul Sharma',
        'email' => 'rahul@tectignis.in',
        'department_id' => $department->id,
        'designation' => 'Tender Executive',
        'status' => EmployeeStatus::Active->value,
    ])->assertRedirect(route('admin.portal.employees.index'));

    $employee = Employee::where('name', 'Rahul Sharma')->first();

    expect($employee)->not->toBeNull()
        ->and($employee->employee_code)->toStartWith('EMP-')
        ->and($employee->department_id)->toBe($department->id);
});

it('grants portal access when a director links a login and a role', function () {
    [$director] = portalUser(PortalRole::Director);
    $newUser = User::factory()->create(['portal_role' => null]);

    $this->actingAs($director)->post(route('admin.portal.employees.store'), [
        'name' => 'Priya Nair',
        'status' => EmployeeStatus::Active->value,
        'user_id' => $newUser->id,
        'portal_role' => PortalRole::Employee->value,
    ])->assertRedirect();

    expect($newUser->refresh()->portalRole())->toBe(PortalRole::Employee);
});

it('does not let a manager change somebody\'s portal role', function () {
    [$manager] = portalUser(PortalRole::Manager);
    $target = User::factory()->create(['portal_role' => null]);

    $this->actingAs($manager)->post(route('admin.portal.employees.store'), [
        'name' => 'Escalation Attempt',
        'status' => EmployeeStatus::Active->value,
        'user_id' => $target->id,
        'portal_role' => PortalRole::Director->value,
    ])->assertRedirect();

    expect($target->refresh()->portal_role)->toBeNull();
});

it('lets an employee edit their own record but not a colleague\'s', function () {
    [$user, $employee] = portalUser(PortalRole::Employee);
    $colleague = Employee::factory()->create();

    $this->actingAs($user)->get(route('admin.portal.employees.edit', $employee))->assertOk();
    $this->actingAs($user)->get(route('admin.portal.employees.edit', $colleague))->assertForbidden();
});

it('rejects an employee reporting to themselves', function () {
    [$director] = portalUser(PortalRole::Director);
    $employee = Employee::factory()->create();

    $this->actingAs($director)->put(route('admin.portal.employees.update', $employee), [
        'name' => $employee->name,
        'status' => EmployeeStatus::Active->value,
        'reports_to_id' => $employee->id,
    ])->assertSessionHasErrors('reports_to_id');
});

it('keeps employee codes unique', function () {
    [$director] = portalUser(PortalRole::Director);
    $existing = Employee::factory()->create(['employee_code' => 'EMP-9999']);

    $this->actingAs($director)->post(route('admin.portal.employees.store'), [
        'name' => 'Duplicate Code',
        'employee_code' => $existing->employee_code,
        'status' => EmployeeStatus::Active->value,
    ])->assertSessionHasErrors('employee_code');
});

it('creates a department and shows its headcount', function () {
    [$director] = portalUser(PortalRole::Director);

    $this->actingAs($director)->post(route('admin.portal.departments.store'), [
        'name' => 'Quality Assurance',
        'code' => 'QA',
        'is_active' => 1,
        'sort_order' => 3,
    ])->assertRedirect(route('admin.portal.departments.index'));

    $department = Department::where('code', 'QA')->first();
    Employee::factory()->count(2)->create(['department_id' => $department->id]);

    $this->actingAs($director)->get(route('admin.portal.departments.index'))
        ->assertOk()
        ->assertSee('Quality Assurance');

    expect($department->employees()->count())->toBe(2);
});

it('keeps department codes unique', function () {
    [$director] = portalUser(PortalRole::Director);
    Department::factory()->create(['code' => 'TEND']);

    $this->actingAs($director)->post(route('admin.portal.departments.store'), [
        'name' => 'Another Tendering',
        'code' => 'TEND',
    ])->assertSessionHasErrors('code');
});
