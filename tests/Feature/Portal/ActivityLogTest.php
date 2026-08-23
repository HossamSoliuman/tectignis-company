<?php

use App\Enums\Portal\PortalRole;
use App\Enums\Portal\TaskStatus;
use App\Models\Portal\ActivityLog;
use App\Models\Portal\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;

uses(RefreshDatabase::class);

it('records who created a task', function () {
    [$director] = portalUser(PortalRole::Director);

    $this->actingAs($director)->post(route('admin.portal.tasks.store'), [
        'title' => 'Audited task',
        'priority' => 'medium',
        'status' => 'not_started',
    ]);

    $log = ActivityLog::where('event', 'created')
        ->where('subject_type', (new Task)->getMorphClass())
        ->first();

    expect($log)->not->toBeNull()
        ->and($log->user_id)->toBe($director->id)
        ->and($log->description)->toContain('created');
});

it('stores a before and after diff on update', function () {
    [$director] = portalUser(PortalRole::Director);
    $task = Task::factory()->create(['title' => 'Original title']);

    $this->actingAs($director)->put(route('admin.portal.tasks.update', $task), [
        'title' => 'Revised title',
        'priority' => $task->priority->value,
        'status' => $task->status->value,
    ]);

    $log = ActivityLog::where('event', 'updated')->latest('id')->first();

    expect($log->changes['before']['title'])->toBe('Original title')
        ->and($log->changes['after']['title'])->toBe('Revised title');
});

it('writes no update entry when nothing actually changed', function () {
    [$director] = portalUser(PortalRole::Director);
    $task = Task::factory()->create();
    $before = ActivityLog::where('event', 'updated')->count();

    $task->update(['title' => $task->title]);

    expect(ActivityLog::where('event', 'updated')->count())->toBe($before);
});

it('records deletion of a task', function () {
    [$director] = portalUser(PortalRole::Director);
    $task = Task::factory()->create();

    $this->actingAs($director)->delete(route('admin.portal.tasks.destroy', $task));

    $this->assertDatabaseHas('portal_activity_logs', [
        'event' => 'deleted',
        'subject_type' => (new Task)->getMorphClass(),
        'subject_id' => $task->id,
    ]);
});

it('records daily work updates too', function () {
    [$user] = portalUser(PortalRole::Employee);

    $this->actingAs($user)->post(route('admin.portal.daily-work.store'), [
        'work_date' => now()->toDateString(),
        'related_category' => 'other',
        'activity' => 'Audited daily work.',
        'status' => 'completed',
    ]);

    expect(ActivityLog::where('subject_type', 'App\Models\Portal\DailyWorkUpdate')->where('event', 'created')->count())->toBe(1);
});

it('exposes no route that can edit or delete the audit trail', function () {
    $writable = collect(Route::getRoutes())
        ->filter(fn ($route): bool => str_contains($route->uri(), 'activity'))
        ->filter(fn ($route): bool => (bool) array_intersect($route->methods(), ['POST', 'PUT', 'PATCH', 'DELETE']));

    expect($writable)->toBeEmpty();
});

it('never overwrites an existing entry when a record changes twice', function () {
    [$director] = portalUser(PortalRole::Director);
    $task = Task::factory()->create();

    $task->update(['status' => TaskStatus::InProgress]);
    $task->update(['status' => TaskStatus::Completed]);

    expect(ActivityLog::forSubject($task)->where('event', 'updated')->count())->toBe(2);
});
