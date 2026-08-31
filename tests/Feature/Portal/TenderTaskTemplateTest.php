<?php

use App\Enums\Portal\TaskStatus;
use App\Models\Portal\Employee;
use App\Models\Portal\Tender;
use App\Services\Portal\TenderTemplateService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('builds the whole workspace from the standard templates', function () {
    $tender = Tender::factory()->create();

    $created = app(TenderTemplateService::class)->apply($tender, Employee::factory()->create());

    expect($created['checklist'])->toBeGreaterThan(0)
        ->and($created['documents'])->toBeGreaterThan(0)
        ->and($created['submission_checks'])->toBeGreaterThan(0)
        ->and($created['tasks'])->toBeGreaterThan(0);
});

it('never applies a template twice', function () {
    $tender = Tender::factory()->create();
    $templates = app(TenderTemplateService::class);

    $first = $templates->apply($tender);
    $second = $templates->apply($tender->refresh());

    expect($second)->toBe(['checklist' => 0, 'documents' => 0, 'submission_checks' => 0, 'tasks' => 0])
        ->and($tender->checklistItems()->count())->toBe($first['checklist'])
        ->and($tender->tasks()->count())->toBe($first['tasks']);
});

it('back-schedules template tasks from the submission deadline', function () {
    $tender = Tender::factory()->create(['submission_deadline_at' => now()->addDays(30)]);

    app(TenderTemplateService::class)->applyTaskTemplate($tender);

    $dates = $tender->tasks()->orderBy('due_date')->pluck('due_date');

    expect($dates->first()->lt($dates->last()))->toBeTrue()
        ->and($dates->last()->lte($tender->submission_deadline_at))->toBeTrue();
});

it('never seeds a task that is born overdue', function () {
    // Two days left: most of the twelve steps would be scheduled in the past.
    $tender = Tender::factory()->create(['submission_deadline_at' => now()->addDays(2)]);

    app(TenderTemplateService::class)->applyTaskTemplate($tender);

    expect($tender->tasks()->where('due_date', '<', now())->count())->toBe(0);
});

it('leaves template tasks undated when the tender has no deadline', function () {
    $tender = Tender::factory()->create(['submission_deadline_at' => null]);

    app(TenderTemplateService::class)->applyTaskTemplate($tender);

    expect($tender->tasks()->whereNotNull('due_date')->count())->toBe(0)
        ->and($tender->tasks()->count())->toBeGreaterThan(0);
});

it('assigns template tasks to the bid owner', function () {
    $owner = Employee::factory()->create();
    $tender = Tender::factory()->create(['assigned_employee_id' => $owner->id]);

    app(TenderTemplateService::class)->applyTaskTemplate($tender);

    expect($tender->tasks()->where('assigned_to_id', '!=', $owner->id)->count())->toBe(0)
        ->and($tender->tasks()->first()->status)->toBe(TaskStatus::NotStarted);
});

it('re-applies the task template on demand for a tender that has none', function () {
    [$director] = portalUser();
    $tender = Tender::factory()->create();

    $this->actingAs($director)->post(route('admin.portal.tenders.tasks.template', $tender))->assertRedirect();

    expect($tender->tasks()->count())->toBeGreaterThan(0);
});

it('gives tender tasks distinct codes and ties them to the tender', function () {
    $tender = Tender::factory()->create();

    app(TenderTemplateService::class)->applyTaskTemplate($tender);

    $tasks = $tender->tasks()->get();

    expect($tasks->pluck('code')->unique())->toHaveCount($tasks->count())
        ->and($tasks->first()->related_id)->toBe($tender->id)
        ->and($tasks->first()->related_type)->toBe($tender->getMorphClass());
});
