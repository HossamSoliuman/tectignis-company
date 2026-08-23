<?php

use App\Enums\Portal\PortalRole;
use App\Models\Portal\DailyWorkUpdate;
use App\Models\Portal\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('submits a daily work update against the logged-in employee', function () {
    [$user, $employee] = portalUser(PortalRole::Employee);

    $this->actingAs($user)->post(route('admin.portal.daily-work.store'), [
        'work_date' => now()->toDateString(),
        'related_category' => 'tender',
        'activity' => 'Compiled the eligibility documents.',
        'start_time' => '09:30',
        'end_time' => '11:45',
        'progress' => 60,
        'status' => 'in_progress',
    ])->assertRedirect(route('admin.portal.daily-work.index'));

    $update = DailyWorkUpdate::first();

    expect($update->employee_id)->toBe($employee->id)
        ->and($update->activity)->toBe('Compiled the eligibility documents.')
        ->and($update->durationMinutes())->toBe(135);
});

it('ignores an employee trying to log work in somebody else\'s name', function () {
    [$user, $employee] = portalUser(PortalRole::Employee);
    $colleague = Employee::factory()->create();

    $this->actingAs($user)->post(route('admin.portal.daily-work.store'), [
        'employee_id' => $colleague->id,
        'work_date' => now()->toDateString(),
        'related_category' => 'other',
        'activity' => 'Trying to log for someone else.',
        'status' => 'completed',
    ])->assertRedirect();

    expect(DailyWorkUpdate::first()->employee_id)->toBe($employee->id);
});

it('lets a manager log work on behalf of their team', function () {
    [$manager] = portalUser(PortalRole::Manager);
    $colleague = Employee::factory()->create();

    $this->actingAs($manager)->post(route('admin.portal.daily-work.store'), [
        'employee_id' => $colleague->id,
        'work_date' => now()->toDateString(),
        'related_category' => 'admin',
        'activity' => 'Logged on behalf of a site engineer.',
        'status' => 'completed',
    ])->assertRedirect();

    expect(DailyWorkUpdate::first()->employee_id)->toBe($colleague->id);
});

it('refuses work logged for a future date', function () {
    [$user] = portalUser(PortalRole::Employee);

    $this->actingAs($user)->post(route('admin.portal.daily-work.store'), [
        'work_date' => now()->addDay()->toDateString(),
        'related_category' => 'other',
        'activity' => 'Time travel.',
        'status' => 'completed',
    ])->assertSessionHasErrors('work_date');

    expect(DailyWorkUpdate::count())->toBe(0);
});

it('refuses an end time that precedes the start time', function () {
    [$user] = portalUser(PortalRole::Employee);

    $this->actingAs($user)->post(route('admin.portal.daily-work.store'), [
        'work_date' => now()->toDateString(),
        'related_category' => 'other',
        'activity' => 'Backwards day.',
        'start_time' => '14:00',
        'end_time' => '09:00',
        'status' => 'completed',
    ])->assertSessionHasErrors('end_time');
});

it('shows an employee only their own day', function () {
    [$user, $employee] = portalUser(PortalRole::Employee);

    DailyWorkUpdate::factory()->forEmployee($employee)->create(['activity' => 'My own activity']);
    DailyWorkUpdate::factory()->create(['activity' => 'A colleague activity']);

    $this->actingAs($user)->get(route('admin.portal.daily-work.index'))
        ->assertOk()
        ->assertSee('My own activity')
        ->assertDontSee('A colleague activity');
});

it('shows managers the whole team\'s day', function () {
    [$manager] = portalUser(PortalRole::Manager);

    DailyWorkUpdate::factory()->create(['activity' => 'A colleague activity']);

    $this->actingAs($manager)->get(route('admin.portal.daily-work.index'))
        ->assertOk()
        ->assertSee('A colleague activity');
});

it('stops an employee editing somebody else\'s update', function () {
    $user = User::factory()->portal(PortalRole::Employee)->create();
    Employee::factory()->create(['user_id' => $user->id]);
    $theirs = DailyWorkUpdate::factory()->create();

    $this->actingAs($user)->get(route('admin.portal.daily-work.edit', $theirs))->assertForbidden();
});

it('keeps deletion of work records with management', function () {
    [$employeeUser, $employee] = portalUser(PortalRole::Employee);
    $update = DailyWorkUpdate::factory()->forEmployee($employee)->create();

    $this->actingAs($employeeUser)->delete(route('admin.portal.daily-work.destroy', $update))->assertForbidden();

    [$director] = portalUser(PortalRole::Director);
    $this->actingAs($director)->delete(route('admin.portal.daily-work.destroy', $update))->assertRedirect();

    expect(DailyWorkUpdate::find($update->id))->toBeNull();
});
