<?php

use App\Enums\Portal\TaskStatus;
use App\Models\Portal\Task;
use App\Services\Portal\OverdueService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('treats an open task past its deadline as overdue', function () {
    $task = Task::factory()->overdue()->create();

    expect($task->isOverdue())->toBeTrue()
        ->and(Task::overdue()->pluck('id'))->toContain($task->id);
});

it('never treats completed or cancelled work as overdue', function (TaskStatus $status) {
    Task::factory()->create(['due_date' => now()->subWeek(), 'status' => $status]);

    expect(Task::overdue()->count())->toBe(0);
})->with([TaskStatus::Completed, TaskStatus::Cancelled]);

it('never treats a task without a deadline as overdue', function () {
    $task = Task::factory()->create(['due_date' => null, 'status' => TaskStatus::InProgress]);

    expect($task->isOverdue())->toBeFalse()
        ->and(Task::overdue()->count())->toBe(0);
});

it('flags overdue work when the refresh runs, with no manual action', function () {
    $task = Task::factory()->overdue()->create(['is_overdue' => false]);

    app(OverdueService::class)->refresh();

    $task->refresh();

    expect($task->is_overdue)->toBeTrue()
        ->and($task->overdue_at)->not->toBeNull();
});

it('clears the flag once the work is completed', function () {
    $task = Task::factory()->overdue()->create();
    app(OverdueService::class)->refresh();

    expect($task->refresh()->is_overdue)->toBeTrue();

    $task->update(['status' => TaskStatus::Completed]);
    app(OverdueService::class)->refresh();

    expect($task->refresh()->is_overdue)->toBeFalse()
        ->and($task->overdue_at)->toBeNull();
});

it('clears the flag when the deadline is pushed out', function () {
    $task = Task::factory()->overdue()->create();
    app(OverdueService::class)->refresh();

    $task->update(['due_date' => now()->addWeek()]);
    app(OverdueService::class)->refresh();

    expect($task->refresh()->is_overdue)->toBeFalse();
});

it('exposes the refresh as a schedulable artisan command', function () {
    Task::factory()->overdue()->create(['is_overdue' => false]);

    $this->artisan('portal:refresh-overdue')->assertSuccessful();

    expect(Task::where('is_overdue', true)->count())->toBe(1);
});

it('counts open, overdue and due-today work for the dashboard', function () {
    Task::factory()->overdue()->create();
    Task::factory()->dueToday()->create(['status' => TaskStatus::InProgress]);
    Task::factory()->completed()->create();

    $counts = app(OverdueService::class)->counts();

    expect($counts['overdue'])->toBe(1)
        ->and($counts['due_today'])->toBe(1)
        ->and($counts['completed'])->toBe(1)
        ->and($counts['open'])->toBe(2);
});
