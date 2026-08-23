<?php

use App\Enums\Portal\PortalRole;
use App\Enums\Portal\TaskStatus;
use App\Models\Portal\Employee;
use App\Models\Portal\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates a task with an auto-generated code', function () {
    [$director] = portalUser();
    $assignee = Employee::factory()->create();

    $this->actingAs($director)->post(route('admin.portal.tasks.store'), [
        'title' => 'Prepare technical compliance sheet',
        'description' => 'For the GeM tender closing Friday.',
        'assigned_to_id' => $assignee->id,
        'priority' => 'high',
        'status' => 'not_started',
        'progress' => 0,
        'due_date' => now()->addDays(3)->format('Y-m-d\TH:i'),
    ])->assertRedirect();

    $task = Task::first();

    expect($task->code)->toStartWith('TSK-')
        ->and($task->assigned_to_id)->toBe($assignee->id)
        ->and($task->title)->toBe('Prepare technical compliance sheet');
});

it('gives each task a distinct code', function () {
    [$director] = portalUser();

    foreach (['First task', 'Second task'] as $title) {
        $this->actingAs($director)->post(route('admin.portal.tasks.store'), [
            'title' => $title,
            'priority' => 'medium',
            'status' => 'not_started',
        ]);
    }

    expect(Task::pluck('code')->unique())->toHaveCount(2);
});

it('forces an employee to assign new work to themselves', function () {
    $user = User::factory()->portal(PortalRole::Employee)->create();
    $employee = Employee::factory()->create(['user_id' => $user->id]);
    $someoneElse = Employee::factory()->create();

    $this->actingAs($user)->post(route('admin.portal.tasks.store'), [
        'title' => 'Self-raised task',
        'assigned_to_id' => $someoneElse->id,
        'priority' => 'medium',
        'status' => 'not_started',
    ])->assertRedirect();

    expect(Task::first()->assigned_to_id)->toBe($employee->id);
});

it('hides other people\'s tasks from an employee', function () {
    $user = User::factory()->portal(PortalRole::Employee)->create();
    $employee = Employee::factory()->create(['user_id' => $user->id]);

    $mine = Task::factory()->assignedTo($employee)->create(['title' => 'My own task']);
    $theirs = Task::factory()->create(['title' => 'Somebody else task']);

    $this->actingAs($user)->get(route('admin.portal.tasks.index'))
        ->assertOk()
        ->assertSee('My own task')
        ->assertDontSee('Somebody else task');

    $this->actingAs($user)->get(route('admin.portal.tasks.show', $theirs))->assertForbidden();
    $this->actingAs($user)->get(route('admin.portal.tasks.show', $mine))->assertOk();
});

it('logs a timeline entry and moves the task when an update is posted', function () {
    $user = User::factory()->portal(PortalRole::Employee)->create();
    $employee = Employee::factory()->create(['user_id' => $user->id]);
    $task = Task::factory()->assignedTo($employee)->create(['status' => TaskStatus::NotStarted, 'progress' => 0]);

    $this->actingAs($user)->post(route('admin.portal.tasks.updates.store', $task), [
        'note' => 'Drafted the compliance matrix.',
        'progress' => 40,
        'status' => TaskStatus::InProgress->value,
    ])->assertRedirect(route('admin.portal.tasks.show', $task));

    $task->refresh();

    expect($task->status)->toBe(TaskStatus::InProgress)
        ->and($task->progress)->toBe(40)
        ->and($task->updates)->toHaveCount(1)
        ->and($task->updates->first()->status_from)->toBe(TaskStatus::NotStarted)
        ->and($task->updates->first()->status_to)->toBe(TaskStatus::InProgress);
});

it('stamps completed_at when a task is completed and counts a reopen', function () {
    [$director, $employee] = portalUser();
    $task = Task::factory()->assignedTo($employee)->create(['status' => TaskStatus::InProgress]);

    $this->actingAs($director)->post(route('admin.portal.tasks.updates.store', $task), [
        'note' => 'Done.',
        'status' => TaskStatus::Completed->value,
    ]);

    expect($task->refresh()->completed_at)->not->toBeNull();

    $this->actingAs($director)->post(route('admin.portal.tasks.updates.store', $task), [
        'note' => 'Client asked for a revision.',
        'status' => TaskStatus::InProgress->value,
    ]);

    $task->refresh();

    expect($task->reopened_count)->toBe(1)
        ->and($task->completed_at)->toBeNull();
});

it('soft deletes a task so its history survives', function () {
    [$director] = portalUser();
    $task = Task::factory()->create();

    $this->actingAs($director)->delete(route('admin.portal.tasks.destroy', $task))->assertRedirect();

    expect(Task::find($task->id))->toBeNull()
        ->and(Task::withTrashed()->find($task->id))->not->toBeNull();
});

it('rejects a deadline that falls before the start date', function () {
    [$director] = portalUser();

    $this->actingAs($director)->post(route('admin.portal.tasks.store'), [
        'title' => 'Impossible schedule',
        'priority' => 'medium',
        'status' => 'not_started',
        'start_date' => now()->addDays(5)->format('Y-m-d\TH:i'),
        'due_date' => now()->addDay()->format('Y-m-d\TH:i'),
    ])->assertSessionHasErrors('due_date');

    expect(Task::count())->toBe(0);
});
